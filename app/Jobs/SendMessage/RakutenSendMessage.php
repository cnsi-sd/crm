<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class RakutenSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    protected Client $client;
    const MESSAGE_LIMIT = 475;
    const PAGE = 'sales_ws';
    const ACTION = 'contactuseraboutitem';
    const VERSION = '2011-02-02';

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function sendMessage(): void
    {
        // If we are not in production environment, we only can send messages to a test order
        if (env('APP_ENV') == 'production')
            if($this->message->thread->channel_thread_number != '868826680')
                return;

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

        $this->logger->info('Init api');
        $client = self::initApiClient();

        $url = $this->getCredentials()['host'] . '/' . self::PAGE;
        $response = $client->post($url, [
            RequestOptions::QUERY => [
                'action' => self::ACTION,
                'itemid' => $threadNumber,
                'content' => $this->prepareMessageForRakuten($this->message->content),
                'login' => env('RAKUTEN_LOGIN'),
                'pwd' => env('RAKUTEN_PASSWORD'),
                'version' => self::VERSION,
            ],
        ]);

        if($response->getStatusCode() != '200')
            throw new Exception('contactuseraboutitem api request gone bad');

        $statusResponse = $this->xmlResponseToArray($response->getBody()->getContents());
        $this->logger->info('Sending message status = '.$statusResponse);
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

    private function prepareMessageForRakuten($message): string
    {
        //Charset must be is Iso, not UTF-8. Html codes are not authorized.
        $message =  mb_convert_encoding($message, 'ISO-8859-1');
        $contentLen = strlen($message);
        if ($contentLen > self::MESSAGE_LIMIT) {
            $message = substr($message, 0, self::MESSAGE_LIMIT);
        }
        return $message;
    }

    private function xmlResponseToArray($xml): bool|string
    {
        $status = false;
        if (strlen($xml) > 0) {
            $data = simplexml_load_string($xml); //data is a SimpleXMLElement
            if ($data->response) {
                $status = (string)$data->response->status; //default 'SENT'
            }
        }
        return $status;
    }
}
