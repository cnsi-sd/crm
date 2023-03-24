<?php

namespace App\Jobs\AnswerOfferQuestions;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Cnsi\Cdiscount\ClientCdiscount;
use Cnsi\Cdiscount\DiscussionsApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CdiscountAnswerOfferQuestions implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    static public function AnswerOfferQuestions($apiMessage)
    {
        // If we are not in production environment, we don't want to send mail
//        if (env('APP_ENV') != 'production')
//            return;

        $client = new ClientCdiscount(env('CDISCOUNT_USERNAME'), env('CDISCOUNT_PASSWORD'));
        $discussion = new DiscussionsApi($client, env('CDISCOUNT_API_URL'), env('CDISCOUNT_SELLERID'));

        $channel = Channel::getByName(ChannelEnum::CDISCOUNT_FR);
        $responses = $channel->defaultAnswers;

        $cdiscountMessage = array(
            'body' => 'message auto',
            'discussionId' => $apiMessage->getDiscussionId(),
            'salesChannelEternalDiscussionReference' => $apiMessage->getSalesChannelExternalReference(),
            'salesChannel' => $apiMessage->getSalesChannel(),
            'receivers' => array(
                array(
                    'userId' => $apiMessage->getCustomerId(),
                    'userType' => 'Customer'
                )
            )
        );

        $discussion->sendMessageOnExistantDiscussion(json_encode($cdiscountMessage));
    }
}
