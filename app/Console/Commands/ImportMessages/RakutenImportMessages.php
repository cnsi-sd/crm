<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RakutenImportMessages extends AbstractImportMessages
{
    private Client $client;
    private string $FROM_SHOP_TYPE;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';
    const getitemtodolist_version = '2011-09-01';
    const getiteminfos_version = '2017-08-07';

    public function __construct()
    {
        $this->signature =sprintf($this->signature,'rakuten');
        $this->FROM_SHOP_TYPE = '???'; // todo ? get shop type
        return parent::__construct();
    }

    protected function getCredentials(): array
    {
       return [
         'host'     => env('RAKUTEN_API_URL'),
         'login'    => env('RAKUTEN_LOGIN'),
         'password' => env('RAKUTEN_PASSWORD'),
         'token'    => env('RAKUTEN_TOKEN')
       ];
    }

    protected function initApiClient()
    {
        $client = new Client([
            'token' => self::getCredentials()['token'],
            //            'httpversion' => '1.1'
        ]);

        $this->client = $client;
        return $this->client;
    }
    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread)
    {
        // TODO: Implement convertApiResponseToMessage() method.
    }

    /**
     * @throws Exception
     * @throws GuzzleException
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


        $dateSortedList = $this->sortMessagesByDate($threadList);


        $this->logger->info('Get messages');
    }

    private function getItems($client): array
    {
        $this->logger->info('Get thread list');

        $items = $client->request(
            'GET', $this->getCredentials()['host']. '/sales_ws?action=getitemtodolist&login='
            . env('RAKUTEN_LOGIN')
            .'&pwd='. env('RAKUTEN_PASSWORD')
            .'&version='. self::getitemtodolist_version
        )->getBody()
            ->getContents();

        return $this->xmlResponseToArray($items);
        //TODO throw error if not success
    }
    private function xmlResponseToArray($response)
    {
        $messages = array();

        if(strlen($response)>0){
            $data = simplexml_load_string($response); //data is a SimpleXMLElement

            if($data->response){
                $sellerid = (string) $data->response->sellerid;
                $lastversion = (string) $data->response->lastversion;

                $msgs = $data->response->items->item;

                $nbMsgs = count($msgs);

                if($nbMsgs>0){
                    foreach ($msgs as $msg){
                        $msgId = (string)$msg->itemid;
                        $cause = (string)$msg->causes->cause;
                        $messages[$msgId] = $cause;
                    }
                }
            }
        }

        return $messages;
    }

    private function xmlThreadToArray($xml)
    {
        $messages = [];

        if(strlen($xml)>0){
            $data = simplexml_load_string($xml); //data is a SimpleXMLElement

            $res = $data->response;
            if($res){
                $sellerid = (string) $res->sellerid;
                $lastversion = (string) $res->lastversion;

                $MpOrderId = (string) $res->purchaseid;

                $item = $res->item;

                $sellerAccount = env('RAKUTEN_LOGIN');

                if(!empty($item)){
                    $MpItemId = (string) $item->itemid;

                    $message = [];
                    if(!empty($item->message)){
                        foreach($item->message as $mess){
                            $message['MpOrderId'] = $MpOrderId;
                            $message['MpItemId'] = $MpItemId;
                            $message['MpCustomerId'] = (string) $mess->sender;
                            $message['Recipient'] = (string) $mess->recipient;
                            $message['Date'] = (string) $mess->senddate;
                            $message['Message'] = trim($this->removeCdata($mess->content));
                            $message['Status'] = (string) $mess->status;
                            if($sellerAccount != $message['MpCustomerId']){
                                $messages[] = $message;//get only message from customer (don't re import our own answer)
                            }
                        }
                    }

                    if(!empty($item->mail)){
                        foreach($item->mail as $mail){
                            $message['MpOrderId'] = $MpOrderId;
                            $message['MpItemId'] = $MpItemId;
                            $message['MpCustomerId'] = (string) $mail->sender;
                            $message['Recipient'] = (string) $mail->recipient;
                            $message['Date'] = (string) $mail->senddate;
                            $message['Object'] = trim($this->removeCdata($mail->object));
                            $message['Message'] = trim($this->removeCdata($mail->content));
                            $message['Status'] = (string) $mail->status;
                            if($sellerAccount != $message['MpCustomerId']){
                                $messages[] = $message;//get only message from customer (don't re import our own answer)
                            }
                        }
                    }
                }
            }
        }

        return $messages;
    }

    private function getInfos($msgsId, $client)
    {
        $this->logger->info('Get messages list');
        $array = [];
        foreach ( $msgsId as $msgId => $type){
            $messages = $client->request(
                'GET', $this->getCredentials()['host']. '/sales_ws?action=getiteminfos&login='
                . env('RAKUTEN_LOGIN')
                .'&pwd='. env('RAKUTEN_PASSWORD')
                .'&version='. self::getiteminfos_version
                .'&itemid=' . $msgId
            )->getBody()
                ->getContents();

            $array[] = $this->xmlThreadToArray($messages);
        }

        return $array;
    }

    protected function removeCdata($fieldString){
        $fieldString = str_replace('<![CDATA[', '', $fieldString);
        $fieldString = str_replace(']]', '', $fieldString);
        return $fieldString;
    }

    private function sortMessagesByDate(array $threadList)
    {
        // Order mails & messages by date
        $message_date_list = [];
        foreach ($threadList as $keyThread => $thread) {

            $message_date_list[$keyThread] = [];

            foreach ($thread as $key => $message){
                $message_date_list[$keyThread][] = date_create_from_format('d/m/Y-H:i',$message['Date'])->getTimestamp();
            }
            array_multisort($message_date_list[$keyThread], $thread);
        }
        array_multisort($message_date_list, $threadList);

        $test = 20+2;

        return $threadList;
    }



}
