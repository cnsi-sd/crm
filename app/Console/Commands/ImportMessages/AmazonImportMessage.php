<?php

namespace App\Console\Commands\ImportMessages;

use App\Console\Commands\ImportMessages\Beautifier\AmazonBeautifierMail;
use App\Enums\Channel\ChannelEnum;
use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\EmailNormalized;
use App\Helpers\TmpFile;
use App\Helpers\Tools;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Attachments\Model\Document;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;

class AmazonImportMessage extends AbstractImportMailMessages
{
    const RETURN = 'retour';
    const IMPORT = 'import';
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'amazon') . ' {account=1}';
        $this->channelName = ChannelEnum::AMAZON_FR;
        $this->reverse = true;
        parent::__construct();
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCredentials(): array
    {
        $accountNumber = $this->argument('account');

        switch ($accountNumber) {
            case 1:
                $host = env('AMAZON_MAIL_URL');
                $username = env('AMAZON_USERNAME');
                $password = env('AMAZON_PASSWORD');
                $client = env('AMAZON_CLIENT');
                break;
            case 2:
                $host = env('AMAZON_2_MAIL_URL');
                $username = env('AMAZON_2_USERNAME');
                $password = env('AMAZON_2_PASSWORD');
                $client = env('AMAZON_2_CLIENT');
                break;
            case 3:
                $host = env('AMAZON_3_MAIL_URL');
                $username = env('AMAZON_3_USERNAME');
                $password = env('AMAZON_3_PASSWORD');
                $client = env('AMAZON_3_CLIENT');
                break;
            default:
                throw new Exception('Account not found');
        }

        return [
            'host' => $host,
            'username' => $username,
            'password' => $password,
            'client' => $client
        ];
    }


    /**
     * @param EmailNormalized $email
     * @return bool
     * @throws Exception
     */
    public function canImport(EmailNormalized $email): bool
    {
        /*
         * No authorized subjects
         */
        $patterns = [
            '#remboursementinitieacutepourlacommande#',
            '#actionrequise#',
            '#amazonfruneouplusieursdevosoffresamazononteacuteteacutesupprimeacuteesdelarecherche#',
            '#offredeacutesactiveacuteesenraisonduneerreurdeprixpotentielle#',
            '#votreemaila#',
        ];
        $normalizedSubject = Tools::normalize($email->getSubject());
        foreach ($patterns as $pattern)
            if (preg_match($pattern, $normalizedSubject))
                return false;

        $patterns = [
            '#autorisationderetourpourlacommande#',
        ];
        foreach ($patterns as $pattern)
            if (preg_match($pattern, $normalizedSubject)) {
                $this->logger->info('subject return command autorized');
                return true;
            }

        return parent::canImport($email);
    }

    /**
     * @param EmailNormalized $email
     * @return bool|string
     */
    public function parseOrderId(EmailNormalized $email): bool|string
    {
        $pattern = '#(?<orderId>\d{3}-\d{7}-\d{7})#';
        preg_match($pattern, $email->getSubject(), $orderId);
        if (isset($orderId['orderId'])) {
            $this->logger->info('OrderId found from Subject '.$orderId['orderId']);
            return $orderId['orderId'];
        }

        preg_match($pattern, $email->getContent(), $orderId);
        if (isset($orderId['orderId'])) {
            $this->logger->info('OrderId found from Body '.$orderId['orderId']);
            return $orderId['orderId'];
        }

        return false;
    }

    /**
     * @param EmailNormalized $email
     * @param $mpOrder
     * @return void
     * @throws Exception
     */
    protected function importEmail(EmailNormalized $email, $mpOrder): void
    {
        $this->logger->info('--- start import email : ' . $email->getEmailId());
        $order = Order::getOrder($mpOrder, $this->channel);
        $ticket = Ticket::getTicket($order, $this->channel);
        $channel_data = ["email" => $email->getFromAddress()];
        $thread = Thread::getOrCreateThread($ticket, Thread::DEFAULT_CHANNEL_NUMBER, $email->getSubject(), $channel_data);

        switch ($this->getSpecificActions($email)) {
            case self::RETURN:
                $this->addReturnOnTicket($ticket, $email);
                break;
            default:
                $this->importMessageByThread($ticket, $thread, $email);
        }
        $this->logger->info('--- end import email');
    }

    /**
     * @param EmailNormalized $email
     * @return string
     */
    protected function getSpecificActions(EmailNormalized $email): string
    {
        $normalizedSubject = Tools::normalize($email->getSubject());
        if (str_contains($normalizedSubject, 'autorisationderetourpourlacommande'))
            return self::RETURN;

        return self::IMPORT;
    }

    /**
     * @param Ticket $ticket
     * @param EmailNormalized $message_api_api
     * @param Thread $thread
     * @param array $attachments
     * @return void
     */
    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread, $attachments = []): void
    {
        $this->logger->info('Retrieve message from email');
        $infoMail = $message_api_api->getContent();
        $message = AmazonBeautifierMail::getCustomerMessage($infoMail);

        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');
        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api_api->getEmailId(),
        ],
            [
                'user_id' => null,
                'author_type' => TicketMessageAuthorTypeEnum::CUSTOMER,
                'content' => strip_tags($message),
            ],
        );

        if ($message_api_api->HasAttachments()) {
            $this->logger->info('Download documents from message');
            foreach ($message_api_api->getAttachments() as $attachment) {
                Document::doUpload($attachment->getTmpFile(), $message, MessageDocumentTypeEnum::OTHER, null, $attachment->getName());
            }
        }

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }

    /**
     * @param Ticket $ticket
     * @param mixed $email
     * @return void
     * @throws Exception
     */
    private function addReturnOnTicket(Ticket $ticket, mixed $email): void
    {
        $tagId = setting('tag.retour_amazon');
        $tag = Tag::findOrFail($tagId);
        $ticket->addTag($tag);

        $returnComment = AmazonBeautifierMail::getReturnInformation($email->getContent());

        $check = Comment::query()
            ->select('*')
            ->where('content' , $returnComment)
            ->where('ticket_id', $ticket->id)
            ->get();

        if ($returnComment !== "" && count($check) == 0) {
            $comment = new Comment();
            $comment->ticket_id = $ticket->id;
            $comment->content = $returnComment;
            $comment->displayed = 1;
            $comment->type = TicketCommentTypeEnum::INFO_IMPORTANT;
            $comment->save();
        }
    }

}
