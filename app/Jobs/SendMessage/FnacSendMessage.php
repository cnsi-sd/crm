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
    static private ?SimpleClient $client = null;

    /**
     * @throws Exception
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::FNAC_COM;
    }

    /**
     * @throws Exception
     */
    protected function getSnakeChannelName(): array|string
    {
        return (new Channel)->getSnakeName($this->getChannelName());
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {

            $this->logger = new Logger('send_message/' . $this->getSnakeChannelName() . '/' . $this->getSnakeChannelName() . '.log', true, true);

            // Variables
            $sendTo = MessageToType::CLIENT;
            $threadNumber = $this->message->thread->channel_thread_number;
            $messageId = Ticket::getLastApiMessageByTicket($threadNumber , $this->getChannelName());

            // Init API client
            $client = $this->initApiClient();

            $query = new MessageUpdate();

            // Answer to message
            $message2 = new Message();
            $message2->setMessageId($messageId);
            $message2->setAction(MessageActionType::REPLY);
            $message2->setMessageTo($sendTo);
            $message2->setMessageSubject(MessageSubjectType::OTHER_QUESTION);
            $message2->setMessageType(MessageType::ORDER);
//            $message2->setMessageDescription($this->translateContent($this->message->content));
            $message2->setMessageDescription( json_encode($this->message->content));
            $query->addMessage($message2);

            /** @var MessageUpdateResponse $messageUpdateResponse */
            $messageUpdateResponse = $client->callService($query);

            if ($messageUpdateResponse->getStatus() !== ResponseStatusType::OK)
                throw new Exception("API push message error");
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
    protected function initApiCLient(): ?SimpleClient
    {
        if(self::$client == null) {
            $client = new SimpleClient();

            $this->logger = new Logger('send_message/' . $this->getSnakeChannelName() . '/' . $this->getSnakeChannelName() . '.log', true, true);
            $client->init(self::getCredentials());
            $client->checkAuth();

            self::$client = $client;
        }

        return self::$client;
    }
}
