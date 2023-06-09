<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class IcozaSendMessage extends AbstractSendMessage
{

    protected Logger $logger;
    static private ?Client $client = null;

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
            'host' => env('ICOZA_API_URL'),
            'key'  => env('ICOZA_API_KEY'),
        ];
    }

    protected function initApiClient()
    {
        if (self::$client == null) {
            $client = new Client([
                RequestOptions::HEADERS => [
                    'token'  => self::getCredentials()['key'],
                    'Accept' => 'application/json',
                ],
            ]);
            self::$client = $client;
        }

        return self::$client;
    }


    public function sendMessage(): void
    {
        // If we are not in production environment, we only can send messages to a test order
        if (env('APP_ENV') == 'production')
            if ($this->message->thread->channel_thread_number != '526-SOOTUCIZG')
                return;

        $this->logger = new Logger('send_message/'
            . $this->getSnakeChannelName()
            . '/' . $this->getSnakeChannelName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');

        // If we are in local environment, we only can send messages to a test order
        if (env('APP_ENV') == 'local')
            if ($this->message->thread->channel_thread_number != '526-SOOTUCIZG')
                return;

            $attachments = $this->message->documents()->get();
            $doc = array();
            foreach ($attachments as $attachment) {
                $doc[] = array(
                    'name' => $attachment->name,
                    'binary' => response()->file($attachment->getFilePath())->getFile()->getContent()
                );
            }

        $threadNumber = $this->message->thread->channel_thread_number;

        $lastApiMessage = Ticket::getLastApiMessageByTicket($threadNumber, $this->getChannelName());
        $this->logger->info('Init api');
        $client = self::initApiClient();

        $route = $this->getCredentials()['host'] . 'Reply';
        $response = $client->request('POST', $route, [
            RequestOptions::FORM_PARAMS => [
                "content" => $this->translateContent($this->message->content),
                "order"   => $lastApiMessage->threadId,
                "documents" => $doc,
            ],
        ]);

        $response->getBody()->getContents();

        // Check response
        if ($response->getReasonPhrase() !== "OK")
            throw new Exception("API push message error");
    }
}
