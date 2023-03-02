<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class RakutenImportMessages extends AbstractImportMessages
{
    private Client $client;
    private string $FROM_SHOP_TYPE;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';
    const getitemtodolist_version = '2011-09-01';
    const getiteminfos_version = '2017-08-07';

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'rakuten');
        $this->FROM_SHOP_TYPE = 'Icoza';
        return parent::__construct();
    }

    private function getAuthorType($authorType)
    {
        return match ($authorType) {
            'CLIENT'        => TicketMessageAuthorTypeEnum::CUSTOMER,
            'Rakuten'    => TicketMessageAuthorTypeEnum::OPERATOR,
            default => throw new Exception('Bad author type.')
        };
    }

    protected function getCredentials(): array
    {
        return [
            'host' => env('RAKUTEN_API_URL'),
            'login' => env('RAKUTEN_LOGIN'),
            'password' => env('RAKUTEN_PASSWORD'),
            'token' => env('RAKUTEN_TOKEN')
        ];
    }

    protected function initApiClient(): Client
    {
        $client = new Client([
            'token' => self::getCredentials()['token'],
        ]);

        $this->client = $client;
        return $this->client;
    }

    protected function convertApiResponseToMessage(Ticket $ticket, $messageApi, Thread $thread)
    {
        $authorType = $messageApi['MpCustomerId'];
        $isNotShopUser = self::isNotShopUser($authorType, $this->FROM_SHOP_TYPE);
        if($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');
            Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $messageApi['id'],
            ],
                [
                    'user_id' => null,
                    'author_type' =>
                        $authorType == 'Rakuten'
                            ? TicketMessageAuthorTypeEnum::OPERATOR
                            : TicketMessageAuthorTypeEnum::CUSTOMER,
                    'content' => strip_tags($messageApi['Message']),
                ],
            );
            if (setting('autoReplyActivate')) {
                $this->logger->info('Send auto reply');
//                self::sendAutoReply(setting('autoReply'), $thread);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->channel = Channel::getByName(ChannelEnum::RAKUTEN_COM);
        $this->logger = new Logger(
            'import_message/'
            . $this->channel->getSnakeName() . '/'
            . $this->channel->getSnakeName()
            . '.log', true, true
        );
        $this->logger->info('--- Start ---');

        // GET LAST MESSAGES
        $this->logger->info('Init api');
        $client = $this->initApiClient();

        $fromTime = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
        $fromDate = date('Y-m-d H:i:s', $fromTime);

        //get item list
        $items = $this->getItems($client);

        //get infos
        $threadList = $this->getInfos($items, $client);
        $threads = $this->sortMessagesByDate($threadList);


        try {
            DB::beginTransaction();
            foreach($threads as $messages) {
                $this->logger->info('Begin Transaction');

                if(isset($messages[0])){
                $order  = Order::getOrder($messages[0]['MpOrderId'], $this->channel);
                $ticket = Ticket::getTicket($order, $this->channel);
                $thread = Thread::getOrCreateThread($ticket, $messages[0]['MpOrderId'], $messages[0]['type'], '');
                $this->importMessageByThread($ticket, $thread, $messages);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            $this->logger->error('An error has occurred. Rolling back.', $e);
            DB::rollBack();
//                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
            return;
        }

        $this->logger->info('Get messages');
    }

    private function getItems($client): array
    {
        $this->logger->info('Get thread list');

        $items = $client->request(
                'GET', $this->getCredentials()['host'] . '/sales_ws?action=getitemtodolist&login='
                . env('RAKUTEN_LOGIN')
                . '&pwd=' . env('RAKUTEN_PASSWORD')
                . '&version=' . self::getitemtodolist_version
            )->getBody()
            ->getContents();

        return $this->xmlItemsToArray($items);
        //TODO throw error if not success
    }

    private function xmlItemsToArray($response): array
    {
        $messages = array();

        if (strlen($response) > 0) {
            $data = simplexml_load_string($response); //data is a SimpleXMLElement

            if ($data->response) {
                $sellerid = (string)$data->response->sellerid;
                $lastversion = (string)$data->response->lastversion;

                $msgs = $data->response->items->item;

                $nbMsgs = count($msgs);

                if ($nbMsgs > 0) {
                    foreach ($msgs as $msg) {
                        $msgId = (string)$msg->itemid;
                        $cause = (string)$msg->causes->cause;
                        $messages[$msgId] = $cause;
                    }
                }
            }
        }

        return $messages;
    }

    private function xmlThreadToArray($xml): array
    {
        $messages = [];

        if (strlen($xml) > 0) {
            $data = simplexml_load_string($xml); //data is a SimpleXMLElement

            $res = $data->response;
            if ($res) {
                $sellerid = (string)$res->sellerid;
                $lastversion = (string)$res->lastversion;

                $MpOrderId = (string)$res->purchaseid;

                $item = $res->item;

                $sellerAccount = env('RAKUTEN_LOGIN');

                if (!empty($item)) {
                    $MpItemId = (string)$item->itemid;

                    $message = [];
                    if (!empty($item->message)) {
                        foreach ($item->message as $mess) {
                            $message['MpOrderId'] = $MpOrderId;
                            $message['MpItemId'] = $MpItemId;
                            $message['MpCustomerId'] = (string)$mess->sender;
                            $message['Recipient'] = (string)$mess->recipient;
                            $message['Date'] = (string)$mess->senddate;
                            $message['Message'] = trim($this->removeCdata($mess->content));
                            $message['Status'] = (string)$mess->status;
                            if ($sellerAccount != $message['MpCustomerId']) {
                                $messages[] = $message;//get only message from customer (don't re import our own answer)
                            }
                        }
                    }

                    if (!empty($item->mail)) {
                        foreach ($item->mail as $mail) {
                            $message['MpOrderId'] = $MpOrderId;
                            $message['MpItemId'] = $MpItemId;
                            $message['MpCustomerId'] = (string)$mail->sender;
                            $message['Recipient'] = (string)$mail->recipient;
                            $message['Date'] = (string)$mail->senddate;
                            $message['Object'] = trim($this->removeCdata($mail->object));
                            $message['Message'] = trim($this->removeCdata($mail->content));
                            $message['Status'] = (string)$mail->status;
                            if ($sellerAccount != $message['MpCustomerId']) {
                                $messages[] = $message;//get only message from customer (don't re import our own answer)
                            }
                        }
                    }
                }
            }
        }

        return $messages;
    }

    private function getInfos($msgsId, $client): array
    {
        $this->logger->info('Get messages list');
        $arrayMessages = [];
        foreach ($msgsId as $msgId => $type) {
            $messages = $client->request(
                'GET', $this->getCredentials()['host'] . '/sales_ws?action=getiteminfos&login='
                . env('RAKUTEN_LOGIN')
                . '&pwd=' . env('RAKUTEN_PASSWORD')
                . '&version=' . self::getiteminfos_version
                . '&itemid=' . $msgId
            )->getBody()
                ->getContents();

            $messagesList = $this->xmlThreadToArray($messages);
            if (isset($messagesList[0]))
                foreach($messagesList as &$msg){
                    $msg['type'] = $type;
                }

            $arrayMessages[] = $messagesList;
        }

        return $arrayMessages;
    }

    protected function removeCdata($fieldString): array|string
    {
        $fieldString = str_replace('<![CDATA[', '', $fieldString);
        $fieldString = str_replace(']]', '', $fieldString);
        return $fieldString;
    }

    private function sortMessagesByDate(array $threads): array
    {
        foreach ($threads as &$messageList) {
            // Build an array that contains only messages dates
            // Important : keys must be the same between messageList and messageTimestampList
            $messageTimestampList = [];
            foreach ($messageList as $key => $message) {
                $messageTimestampList[$key] = DateTime::createFromFormat('d/m/Y-H:i', $message['Date'])->getTimestamp();
            }

            // Sort $messageList based on timestamps contained in the $messageTimestampList
            /** @see array_multisort */
            array_multisort($messageTimestampList, $messageList);
        }

        return $threads;
    }

    /**
     * @throws Exception
     */
    private function importMessageByThread($ticket, Thread $thread, mixed $messages)
    {
        foreach ($messages as $message) {
            $message['id'] = crc32($message['Message'] . $message['MpCustomerId'] . $message['Date']);
            $this->logger->info('Check if this message is imported');
            if(!$this->isMessagesImported($message['id'])){
                $this->logger->info('Convert api message to db message');
                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $this->addImportedMessageChannelNumber($message['id']);
            }
        }
    }
}
