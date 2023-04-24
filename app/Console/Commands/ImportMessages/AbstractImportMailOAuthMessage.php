<?php

namespace App\Console\Commands\ImportMessages;

use App\Console\Commands\ImportMessages\Beautifier\AmazonBeautifierMail;
use App\Enums\Channel\ChannelEnum;
use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
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
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class AbstractImportMailOAuthMessage extends AbstractImportMessages
{
    const RETURN = 'retour';
    const IMPORT = 'import';

    const SPAM_TAG = 'X-Spam-Tag';
    const SPAM_STATUS = 'X-Spam-Status';
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';

    const ALERT_LOCKED_SINCE = 600;
    const KILL_LOCKED_SINCE = 1200;

    /**
     * @var string
     */
    protected string $channelName;
    private $accessToken;


    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'amazonTkg');
        $this->channelName = ChannelEnum::AMAZON_FR;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        $this->channel = Channel::getByName($this->channelName);
        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $this->initApiClient();

            $from_time = strtotime(date('d M Y H:i:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date("Y-m-d H:i:s", $from_time);

            $messageUrlGraph = 'https://graph.microsoft.com/v1.0/me/messages?top=100';

            $curl = curl_init($messageUrlGraph);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $this->accessToken
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                die('Erreur : ' . curl_error($curl));
            }

            $email_data = json_decode($response, true);

            if (!array_key_exists('error', $email_data)) {
                foreach ($email_data['value'] as $email) {
                    try {
                        if (!$this->canImport($email)) {
                            $this->logger->info($email['sender']['emailAddress']['address']);
                            $this->logger->info($email['subject']);
                            $this->logger->info('cannot import email');
                            continue;
                        }

                        $this->logger->info('Retrieve command number from email');
                        $mpOrder = $this->parseOrderId($email);
                        if (!$mpOrder) {
                            $this->logger->error('marketplace order id not found');
                            continue;
                        }

                        $this->logger->info('Begin Transaction');
                        DB::beginTransaction();

                        $this->logger->info('--- start import email : ' . $email['id']);
                        $order = Order::getOrder($mpOrder, $this->channel);
                        $ticket = Ticket::getTicket($order, $this->channel);
                        $thread = Thread::getOrCreateThread($ticket, Thread::DEFAULT_CHANNEL_NUMBER, $email['subject'], ["email" => $email['from']['emailAddress']['address']]);

                        switch ($this->getSpecificActions($email)) {
                            case self::RETURN:
                                $this->addReturnOnTicket($ticket, $email);
                                break;
                            default:
                                $this->importMessageByThread($ticket, $thread, $email);
                        }
                        $this->logger->info('--- end import email');

                        $this->logger->info('Email imported');
                        DB::commit();
                    } catch (Exception $e) {
                        $this->logger->error('An error has occurred. Rolling back.', $e);
                        DB::rollBack();
                        \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                        return;
                    }
                }
            } else {
                $this->logger->error($email_data['error']['code']);
                $this->logger->error($email_data['error']['message']);
            }
        } catch (Exception $e) {
            $this->logger->error('An error has occurred. Rolling back.', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }

    protected function getCredentials(): array
    {
        return [
            'API_ID' => config('azure.appId'),
            'API_SECRET' => config('azure.appSecret'),
            'API_REDIRECT_URI' => config('azure.redirectUri'),
            'API_ENDPOINT' => config('azure.authority') . config('azure.authorizeEndpoint'),
            'API_TOKEN_ENDPOINT' => config('azure.authority') . config('azure.tokenEndpoint'),
            'API_RESSOURCE_OWNER_DETAILS' => '',
            'API_SCOPES' => config('azure.scopes')
        ];
    }

    /**
     * @throws IdentityProviderException
     */
    protected function initApiClient(): void
    {
        $credentials = $this->getCredentials();
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => $credentials['API_ID'],
            'clientSecret' => $credentials['API_SECRET'],
            'redirectUri' => $credentials['API_REDIRECT_URI'],
            'urlAuthorize' => $credentials['API_ENDPOINT'],
            'urlAccessToken' => $credentials['API_TOKEN_ENDPOINT'],
            'urlResourceOwnerDetails' => $credentials['API_RESSOURCE_OWNER_DETAILS'],
            'scopes' => $credentials['API_SCOPES']
        ]);

        if (setting('TKGAccessToken') !== null || setting('TKGTokenExpiredTime') !== null && setting('TKGTokenExpiredTime') < time()) {
            $accessToken = $oauthClient->getAccessToken('refresh_token', [
                'refresh_token' => setting('TKGRefreshToken')
            ]);

            setting(['TKGAccessToken' => $accessToken->getToken()]);
            setting(['TKGRefreshToken' => $accessToken->getRefreshToken()]);
            setting(['TKGTokenExpiredTime' => $accessToken->getExpires()]);
            setting()->save();
        }
        $this->accessToken = setting('TKGAccessToken');
    }

    /**
     * @param $email
     * @return bool
     * @throws Exception
     */
    protected function canImport($email): bool
    {
        if (str_contains($email['sender']['emailAddress']['address'], 'amazon')) {
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
            $normalizedSubject = Tools::normalize($email['subject']);
            foreach ($patterns as $pattern)
                if (preg_match($pattern, $normalizedSubject))
                    return false;

            $patterns = [
                '#autorisationderetourpourlacommande#',
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $normalizedSubject)) {
                    $this->logger->info('subject return command autorized');
                    return true;
                }
            }

            $starter_date = $this->checkMessageDate(new DateTime($email['receivedDateTime']));
            if (!$starter_date)
                return false;

            preg_match('/@(.*)/', $email['sender']['emailAddress']['address'], $match);
            if (in_array($match[1], config('email-import.domain_blacklist'))) {
                $this->logger->info('Domaine blacklist');
                return false;
            }

            if (in_array($email['sender']['emailAddress']['address'], config('email-import.email_blacklist'))) {
                $this->logger->info('Email blacklist');
                return false;
            }

            if ($this->isSpam($email)) {
                if (in_array($match[1], config('email-import.domain_whitelist'))) {
                    $this->logger->info('Domaine blacklist');
                    return true;
                }
                if (in_array($match[1], config('email-import.email_whitelist'))) {
                    $this->logger->info('Domaine blacklist');
                    return true;
                }
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    protected function isSpam($email): bool
    {
        $spamSign = [self::SPAM_TAG, self::SPAM_STATUS];
        foreach ($spamSign as $spam) {
            if (str_contains($spam, $email['body']['content'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $email
     * @return bool|string
     */
    public function parseOrderId($email): bool|string
    {
        $pattern = '#(?<orderId>\d{3}-\d{7}-\d{7})#';
        preg_match($pattern, $email['subject'], $orderId);
        if (isset($orderId['orderId'])) {
            $this->logger->info('OrderId found from Subject ' . $orderId['orderId']);
            return $orderId['orderId'];
        }

        preg_match($pattern, $email['body']['content'], $orderId);
        if (isset($orderId['orderId'])) {
            $this->logger->info('OrderId found from Body ' . $orderId['orderId']);
            return $orderId['orderId'];
        }

        return false;
    }

    /**
     * @param $email
     * @return string
     */
    protected function getSpecificActions($email): string
    {
        $normalizedSubject = Tools::normalize($email['subject']);
        if (str_contains($normalizedSubject, 'autorisationderetourpourlacommande'))
            return self::RETURN;

        return self::IMPORT;
    }

    /**
     * @param Ticket $ticket
     * @param $message_api_api
     * @param Thread $thread
     * @return void
     */
    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread, $attachments = []): void
    {
        $this->logger->info('Retrieve message from email');
        $infoMail = $message_api_api['body']['content'];
        $message = AmazonBeautifierMail::getCustomerMessage($infoMail);

        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');
        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api_api['id'],
        ],
            [
                'user_id' => null,
                'author_type' => TicketMessageAuthorTypeEnum::CUSTOMER,
                'content' => strip_tags($message),
            ],
        );
        if ($message_api_api['hasAttachements']) {
            $this->logger->info('Download documents from message');
            $url = "https://graph.microsoft.com/v1.0/me/messages/{$message_api_api['id']}/attachments";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/json"
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $attachments = json_decode($response)->value;

            foreach ($attachments as $attachment) {
                $url = "https://graph.microsoft.com/v1.0/me/messages/{$message_api_api['id']}/attachments/{$attachment->id}";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Bearer {$this->accessToken}",
                    "Content-Type: application/json"
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                // Récupérer le contenu de la pièce jointe
                $content = json_decode($response)->contentBytes;

                $tmpFile = new TmpFile((string)$content);
                Document::doUpload($tmpFile, $message, MessageDocumentTypeEnum::OTHER, null, $attachment->name);
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
        $tag = Tag::findOrFail(146);
        $ticket->addTag($tag);

        $returnComment = AmazonBeautifierMail::getReturnInformation($email['body']['content']);

        $check = Comment::query()
            ->select('*')
            ->where('content', $returnComment)
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

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param $email
     * @return void
     * @throws Exception
     */
    protected function importMessageByThread(Ticket $ticket, Thread $thread, $email): void
    {
        $this->logger->info('Check if this message is imported');
        if ($this->isMessagesImported($email['id']))
            return;

        $this->logger->info('Convert email to message');
        $this->convertApiResponseToMessage($ticket, $email, $thread);
        $this->addImportedMessageChannelNumber($email['id']);
    }
}
