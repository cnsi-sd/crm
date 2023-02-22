<?php

namespace App\Jobs\SendMessage;

use Cnsi\Cdiscount\DiscussionsApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket\Message;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CdiscountSendMessage extends AbstractSendMessage
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function handle(): void
    {
        $discussion = new DiscussionsApi('CNSI SD', '3008c6aa-6043-457a-abd5-81439911e20d', 33222);

        $channel_data = json_decode($this->message->thread->channel_data);

        $cdiscountMessage = array(
            'body' => $this->message->content,
            'discussionId' => $this->message->thread->channel_thread_number,
            'salesChannelEternalDiscussionReference' => $channel_data->salesChannelExternalReference,
            'salesChannel' => $channel_data->salesChannel,
            'receivers' => array(
                array(
                    'userId' => $channel_data->userId,
                    'userType' => 'Customer'
                )
            )
        );

        $discussion->sendMessageOnExistantDiscussion(json_encode($cdiscountMessage));

    }
}
