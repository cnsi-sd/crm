<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\AbstractController;
use App\Models\Channel\Channel;
use Illuminate\Contracts\View\View;

class AnswerOfferQuestionController extends AbstractController
{
    public function getMessageContent($channelName)
    {
        $channel = Channel::getByName($channelName);

        $messageContent = setting();
    }

    public function getSettingKey($channelName): string
    {
        return 'answerOfferQuestion.' . strtolower($channelName);
    }
}
