<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use BompApiClient\Client\SimpleClient;
use BompApiClient\Entity\Message;
use BompApiClient\Exception\ErrorResponseException;
use BompApiClient\Service\Request\MessageUpdate;
use BompApiClient\Service\Response\MessageUpdateResponse;
use BompApiClient\Type\MessageActionType;
use BompApiClient\Type\MessageSubjectType;
use BompApiClient\Type\MessageToType;
use BompApiClient\Type\MessageType;
use BompApiClient\Type\ResponseStatusType;
use Cnsi\Logger\Logger;
use Exception;

abstract class AbstractBompSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    private SimpleClient $client;
    abstract protected function getCredentials(): array;

    /**
     * @throws Exception
     */
    public function sendMessage(): void
    {
        // If we are not in production environment, we only can send messages to a test order
        if (env('APP_ENV') !== 'production')
            if( $this->message->thread->channel_thread_number != 'FICGB4UDKJXMM')
                return;

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
        $orderNumber = $this->message->thread->ticket->order->channel_order_number;

        // Init API client
        $this->logger->info('Init api');
        $client = $this->initApiClient();

        $query = new MessageUpdate();

        // Answer to message
        $message2 = new Message();
        $message2->setMessageId($orderNumber);
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

        $this->logger->info('Message ' . $orderNumber . ' sent with API response ' . $messageUpdateResponse->getStatus());
        $this->logger->info('--- END ---');
    }

    /**
     * @throws ErrorResponseException
     */
    protected function initApiCLient(): SimpleClient
    {
        $this->client = new SimpleClient();
        $this->client->init($this->getCredentials());
        $this->client->checkAuth();

        return $this->client;
    }
}
