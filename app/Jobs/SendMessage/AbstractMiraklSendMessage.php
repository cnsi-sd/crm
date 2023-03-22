<?php

namespace App\Jobs\SendMessage;

use Mirakl\MMP\Common\Domain\Collection\Message\Thread\ThreadRecipientCollection;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadRecipient;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadReplyMessageInput;
use Mirakl\MMP\Common\Request\Message\ThreadReplyRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;

abstract class AbstractMiraklSendMessage extends AbstractSendMessage
{
    const CUSTOMER = 'CUSTOMER';

    const HTTP_CONNECT_TIMEOUT = 15;

    abstract protected function getCredentials(): array;

    public function sendMessage(): void
    {
    // If we are not in production environment, we only can send messages to a test order
        if (env('APP_ENV') != 'production')
            if($this->message->thread->channel_thread_number != '07b62278-0faa-41da-9278-d19550eda712')
               return;

        $thread_id = $this->message->thread->channel_thread_number;

        // Variables
        $send_to = [self::CUSTOMER];

        // Init API client
        $client = $this->initApiClient();

        // Build Mirakl recipients
        $recipients = new ThreadRecipientCollection();
        foreach ($send_to as $type) {
            $rec = new ThreadRecipient();
            $rec->setType($type);
            $recipients->add($rec);
        }

        // Build Mirakl message
        $messageToAnswer = new ThreadReplyMessageInput();
        $messageToAnswer->setTo($recipients);
        $messageToAnswer->setBody($this->message->content);

        // Send request
        $request = new ThreadReplyRequest($thread_id, $messageToAnswer);
        $client->replyToThread($request);
    }

    /**
     * @return ShopApiClient
     */
    protected function initApiClient(): ShopApiClient
    {
        $credentials = $this->getCredentials();
        $client = new ShopApiClient(
            $credentials['API_URL'],
            $credentials['API_KEY'],
            $credentials['API_SHOP_ID'],
        );
        $client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $client;
    }
}
