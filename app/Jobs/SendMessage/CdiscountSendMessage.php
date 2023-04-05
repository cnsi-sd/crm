<?php

namespace App\Jobs\SendMessage;

use Cnsi\Cdiscount\ClientCdiscount;
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
    public function sendMessage(): void
    {
        // If we are not in production environment, we don't want to send mail
        if (env('APP_ENV') != 'production')
            if($this->message->thread->channel_thread_number != '63f370d8bf30f2b3e250d3ea') // commande cdiscount 1055472612-A, userId 00000017T2KW
                return;

        $client = new ClientCdiscount(env('CDISCOUNT_USERNAME'), env('CDISCOUNT_PASSWORD'));
        $discussion = new DiscussionsApi($client, env('CDISCOUNT_API_URL'), env('CDISCOUNT_SELLERID'));

        $channel_data = json_decode($this->message->thread->channel_data);

        $attachments = $this->message->documents()->get();
        $attachmentsToSend = [];

        // Add attachments
        if($attachments->count() > 0) {
            foreach ($attachments as $attachment) {
                $attachmentsToSend[] = array(
                    'content' => base64_encode(response()->file($attachment->getFilePath())->getFile()->getContent()),
                    'name' => pathinfo($attachment->name, PATHINFO_FILENAME),
                    'fileFormat' => pathinfo($attachment->name, PATHINFO_EXTENSION)
                );
            }
        }

        $cdiscountMessage = array(
            'body' => $this->message->content,
            'attachments' => $attachmentsToSend, // optional
            'discussionId' => $this->message->thread->channel_thread_number,
            'salesChannelExternalDiscussionReference' => $channel_data->salesChannelExternalReference, // optional
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
