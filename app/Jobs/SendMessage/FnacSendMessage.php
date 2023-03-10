<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use FnacApiClient\Client\SimpleClient;
use FnacApiClient\Entity\Message;
use FnacApiClient\Exception\ErrorResponseException;
use FnacApiClient\Service\Request\MessageUpdate;
use FnacApiClient\Service\Response\MessageUpdateResponse;
use FnacApiClient\Type\MessageActionType;
use FnacApiClient\Type\MessageSubjectType;
use FnacApiClient\Type\MessageToType;
use FnacApiClient\Type\MessageType;
use FnacApiClient\Type\ResponseStatusType;

class FnacSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    private SimpleClient $client;

    /**
     * @throws Exception
     */
    public function sendMessage(): void
    {
        // If we are in local environment, we only can send messages to a test order
        if (env('APP_ENV') == 'local')
            if( $this->message->thread->channel_thread_number != 'FICGB4UDKJXMM')
                return;

        try {
            // Load channel
            $this->channel = Channel::getByName(ChannelEnum::FNAC_COM);
            $this->logger = new Logger('send_message/'
                . $this->channel->getSnakeName()
                . '/' . $this->channel->getSnakeName()
                . '.log', true, true
            );

            $this->logger->info('--- Start ---');

            // Variables
            $sendTo = MessageToType::CLIENT;
            $threadNumber = $this->message->thread->channel_thread_number;

            $lastApiMessage = Ticket::getLastApiMessageByTicket($threadNumber , $this->channel->name);

            // Init API client
            $this->logger->info('Init api');
            $client = $this->initApiClient();

            $query = new MessageUpdate();

            // Answer to message
            $message2 = new Message();
            $message2->setMessageId($lastApiMessage->messageId);
            $message2->setAction(MessageActionType::REPLY);
            $message2->setMessageTo($sendTo);
            $message2->setMessageSubject(MessageSubjectType::OTHER_QUESTION);
            $message2->setMessageType(MessageType::ORDER);
            $message2->setMessageDescription( $this->translateContent($this->message->content));
            $query->addMessage($message2);

            /** @var MessageUpdateResponse $messageUpdateResponse */
            $messageUpdateResponse = $client->callService($query);

            // Check response
            if ($messageUpdateResponse->getStatus() !== ResponseStatusType::OK)
                throw new Exception("API push message error");

            $this->logger->info('Message ' . $lastApiMessage->messageId . ' sent with API response ' . $messageUpdateResponse->getStatus());
            $this->logger->info('--- END ---');

        } catch (Exception $e) {
            $this->logger->error('An error has occurred while sending message.', $e);

//            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
            return;
        }
    }

    protected function getCredentials(): array
    {
        return [
            'host'       => env('FNAC_API_URL'),
            'shop_id'    => env('FNAC_API_SHOP_ID'),
            'key'        => env('FNAC_API_KEY'),
            'partner_id' => env('FNAC_API_PARTNER_ID'),
        ];
    }

    /**
     * @throws ErrorResponseException
     * @throws Exception
     */
    protected function initApiCLient(): SimpleClient
    {
        $client = new SimpleClient();
        $client->init($this->getCredentials());
        $client->checkAuth();
        $this->client = $client;

        return $this->client;
    }
}
