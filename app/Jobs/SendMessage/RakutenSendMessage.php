<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use GuzzleHttp\Client;

class RakutenSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    protected Client $client;
    const MESSAGE_LIMIT = 475;
    const PAGE = 'sales_ws';
    const ACTION = 'contactuseraboutitem';
    const VERSION = '2011-02-02';
    public function handle(): void
    {
        try {
            // Load channel
            $this->channel = Channel::getByName(ChannelEnum::RAKUTEN_COM);
            $this->logger = new Logger('send_message/'
                . $this->channel->getSnakeName()
                . '/' . $this->channel->getSnakeName()
                . '.log', true, true
            );
            $this->logger->info('--- Start ---');

            /**
             * @param Message $lastApiMessage
             */
            // Variables
            $threadNumber = $this->message->thread->channel_thread_number;
            $lastApiMessage = Ticket::getLastApiMessageByTicket($threadNumber , $this->channel->name);

            $this->logger->info('Init api');
            $client = self::initApiClient();

            $xmlResponse = $client->request(
                'POST', $this->getCredentials()['host'] . '/' . self::PAGE
                . '?action='    . self::ACTION
                . '?itemid='    . $lastApiMessage->channel_message_number
                . '?content='   . $this->prepareMessageForPriceMinister($this->message->content)
                . '?login='     . env('RAKUTEN_LOGIN')
                . '?pwd'        . env('RAKUTEN_PASSWORD')
                . '?version='   . self::VERSION
            );
            $array = $this->xmlResponseToArray($xmlResponse);
            $test = '';
        }  catch (Exception $e) {
            $this->logger->error('An error has occurred while sending message.', $e);
//            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
            return;
        }
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

    private function prepareMessageForPriceMinister($message): string
    {
        $message = html_entity_decode(strip_tags(trim($message)), ENT_NOQUOTES, "ISO-8859-15");
        $contentLen = strlen($message);
        if ($contentLen > self::MESSAGE_LIMIT) {
            $message = substr($message, 0, self::MESSAGE_LIMIT);
        }
        return $message;
    }

    private function xmlResponseToArray($xml)
    {
        $status = false;
        if (strlen($xml) > 0) {
            $data = simplexml_load_string($xml); //data is a SimpleXMLElement
            if ($data->response) {
                $status = (string)$data->response->status; //default 'SENT'
                $lastversion = (string)$data->response->lastversion;
//                $this->updateModuleVersion($lastversion);
            }
        }
        return $status;
    }
}
