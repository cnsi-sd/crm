<?php

namespace App\Jobs\AnswerOfferQuestions;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use Cnsi\Cdiscount\ClientCdiscount;
use Cnsi\Cdiscount\DiscussionsApi;
use Cnsi\Logger\Logger;
use Exception;
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

    private Logger $logger;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    static public function AnswerOfferQuestions($apiMessage)
    {
        // If we are not in production environment, we don't want to send mail
        if (env('APP_ENV') != 'production')
            return;


        if (!setting('daoq.active')) // We check if the dedicated setting is activated
            return;

        $channel = Channel::getByName(ChannelEnum::CDISCOUNT_FR);

        $logger = new Logger('AnswerOfferQuestion/'
            . $channel->getSnakeName()
            . '/' . $channel->getSnakeName()
            . '.log', true, true
        );
        $logger->info('--- Auto answer offer question ---');
        $client = new ClientCdiscount(env('CDISCOUNT_USERNAME'), env('CDISCOUNT_PASSWORD'));
        $discussion = new DiscussionsApi($client, env('CDISCOUNT_API_URL'), env('CDISCOUNT_SELLERID'));

        $logger->info(
            'Answer to discussionId: ' . $apiMessage->getDiscussionId()
            . ', customerId: ' . $apiMessage->getCustomerId());

        $defaultAnswerId = setting('default_answer_offer_questions');
        $messageBody = DefaultAnswer::findOrFail($defaultAnswerId);

        $cdiscountMessage = array(
            'body' => $messageBody,
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

        $logger->info('Auto answer sent');
    }
}
