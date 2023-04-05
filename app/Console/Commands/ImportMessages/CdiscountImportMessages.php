<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\TmpFile;
use App\Jobs\AnswerOfferQuestions\CdiscountAnswerOfferQuestions;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Attachments\Model\Document;
use Cnsi\Cdiscount\ClientCdiscount;
use Cnsi\Cdiscount\DiscussionsApi;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CdiscountImportMessages extends AbstractImportMessages
{
    /**
     * @var ClientCdiscount
     */
    static private ClientCdiscount $client;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    const MAX_RETRY_API_CALL = 5;
    const IGNORE_MSG_CONTAINS = [
        '----- The following addresses had permanent fatal errors -----',
        'THIS IS A WARNING MESSAGE ONLY',
        'THIS IS A WARNING ONLY.',
        'Votre demande d’annulation a été acceptée. Le remboursement est en cours.',
    ];

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'cdiscount');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws Exception|TransportExceptionInterface
     */
    public function handle()
    {
        $this->channel = Channel::getByName(ChannelEnum::CDISCOUNT_FR);

        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $from_time = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date('Y-m-d H:m:i', $from_time);

            $this->logger->info('--- Init api client ---');
            $this->initApiClient();
            $discussion = new DiscussionsApi(self::$client,env('CDISCOUNT_API_URL'), env('CDISCOUNT_SELLERID'));
            $this->logger->info('--- Get all discussions ---');
            $listDiscussionId = $discussion->getAllDiscussions($from_date);

            $this->logger->info('--- Get details of discussion ---');
            $allDiscussion = $discussion->getAllDiscussionDetails($listDiscussionId);
        } catch (Exception $e) {
            $this->logger->error('An error has occurred on API call', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
        foreach ($allDiscussion as $discu){
            try {
                $this->logger->info('--- check chat modification time ---');
                if ($discu->getUpdatedAt()->getTimestamp() > $from_time) {

                    if ($discu->getTypologyCode() == "Offer") {
                        CdiscountAnswerOfferQuestions::AnswerOfferQuestions($discu);
                    } else {
                        $this->logger->info('Begin Transaction');
                        DB::beginTransaction();

                        if (!$discu->isOpen())
                            $this->checkClosedDiscussion($discu);

                        $this->logger->info('Message recovery');
                        $messages = $discu->getMessages();
                        
                        $this->logger->info('Check if dicussion have messages');
                        if (count($messages) == 0)
                            continue;

                        foreach ($messages as $message) {
                            $this->logger->info('Check message sender');
                            $authorType = $message->getSender()->getUserType();

                            $attachments = $discussion->getAttachmentsFromMessage($message->getSalesChannelExternalReference(),$message->getMessageId());

                            if ($authorType == 'Seller')
                                continue;

                            $orderReference = $discu->getOrderReference();
                            $order = Order::getOrder($orderReference, $this->channel);
                            $ticket = Ticket::getTicket($order, $this->channel);

                            $channel_data = [
                                "salesChannelExternalReference" => $discu->getSalesChannelExternalReference(),
                                "salesChannel" => $discu->getSalesChannel(),
                                "userId" => $discu->getCustomerId(),
                            ];
                            $thread = Thread::getOrCreateThread($ticket, $discu->getDiscussionId(), $discu->getSubject(), $channel_data);

                            $this->importMessageByThread($ticket, $thread, $message, $attachments);
                            $this->logger->info('---- End Import Message');
                            DB::commit();
                        }
                    }
                }
            } catch (Exception $e){
                $this->logger->error('An error has occurred. Rolling back.', $e);
                DB::rollBack();
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }
    protected function getCredentials(): array
    {
        return [
            'API_USERNAME'  => env('CDISCOUNT_USERNAME'),
            'API_KEY'       => env('CDISCOUNT_PASSWORD'),
        ];
    }

    protected function initApiClient(){
        $credentials = $this->getCredentials();
        self::$client = new ClientCdiscount(
            $credentials['API_USERNAME'],
            $credentials['API_KEY']
        );
    }

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param array $messages
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, Thread $thread,$message, $attachments)
    {
        $imported_id = $message->getMessageId();
        $this->logger->info('Check if this message is imported');

        if (!$this->isMessagesImported($imported_id)) {
            $this->logger->info('Convert api message to db message');
            $this->convertApiResponseToMessage($ticket, $message, $thread, $attachments);
            $this->addImportedMessageChannelNumber($imported_id);
        }
    }

    private function checkClosedDiscussion($discussion)
    {
        $this->logger->info('Discussion is closed : add tag to existing ticket');
        $ticket = Ticket::select('tickets.*')
            ->join('orders', 'orders.id', 'tickets.order_id')
            ->where('tickets.channel_id', $this->channel->id)
            ->where('orders.channel_order_number', $discussion->getOrderReference())
            ->first();

        if ($ticket){
            $closedTagId = setting('closed_discussion_tag_id');
            $closedTag = Tag::findOrfail($closedTagId);

            if(!$ticket->hastag($closedTag))
                $ticket->addTag($closedTag);
        }
    }

    /**
     * Convert api messages into message model in order to save it in database
     * @param Ticket $ticket
     * @param $message_api
     * @param Thread $thread
     * @throws Exception
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api, Thread $thread, $attachments = [])
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->save();
        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api->getMessageId(),
        ],
            [
                'user_id' => null,
                'author_type' => self::getAuthorType($message_api->getSender()->getUserType()),
                'content' => strip_tags($message_api->getBody()),
            ]
        );

        if ($attachments) {
            $this->logger->info('Download documents from message');
            foreach ($attachments as $attachment) {
                $tmpFile = new TmpFile((string) base64_decode($attachment['content']));
                Document::doUpload($tmpFile, $message, MessageDocumentTypeEnum::OTHER, $attachment['fileFormat'], $attachment['attachmentName']);
            }
        }

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }

    /**
     * @throws Exception
     */
    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'Customer' => TicketMessageAuthorTypeEnum::CUSTOMER, //
            'GrcOperator' => TicketMessageAuthorTypeEnum::OPERATOR, //
            default => throw new Exception('Bad author type.')
        };
    }
}
