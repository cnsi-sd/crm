<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use GuzzleHttp\Client;

class IcozaSendMessage extends AbstractSendMessage
{

    protected Logger $logger;
    static private  ?Client $client = null;

    /**
     * @throws Exception
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::ICOZA_FR;
    }

    /**
     * @throws Exception
     */
    protected function getSnakeChannelName(): array|string
    {
        return (new Channel)->getSnakeName($this->getChannelName());
    }

    protected function getCredentials(): array
    {
        return [
            'host'  => env('ICOZA_API_URL'),
            'key'   => env('ICOZA_API_KEY'),
        ];
    }
    protected function initApiClient()
    {
        if(self::$client == null) {

            $client = new Client([
                'headers' => [
                    'token' => self::getCredentials()['key'],
                    'Accept' => 'application/json',
                ],
            ]);
            self::$client = $client;
        }

        return self::$client;
    }


    public function handle(): void
    {
        try {
            $this->logger = new Logger('send_message/'
                . $this->getSnakeChannelName()
                . '/' . $this->getSnakeChannelName()
                . '.log', true, true
            );

            $this->logger->info('--- Start ---');

            $threadNumber = $this->message->thread->channel_thread_number;
            $lastApiMessage = Ticket::getLastApiMessageByTicket($threadNumber , $this->getChannelName());

            $this->logger->info('Init api');
            $client = self::initApiClient();

            $response = $client->request('POST', $this->getCredentials()['host']. 'Reply',
                [
                    'content' => $this->translateContent($lastApiMessage->messageContent),
                    'order' => $lastApiMessage->orderId,
                ]
            );

            // Check response
            if ($response->getReasonPhrase() !== "OK")
                throw new Exception("API push message error");

        } catch (Exception $e) {
            $this->logger->error('An error has occurred while sending message.', $e);

//            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
            return;
        }
    }
}
