<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\AbstractController;
use Illuminate\Http\Request;


class AnswerOfferQuestionController extends AbstractController
{
    public function getMessageContent(Request $request)
    {
        $channelName = $request->input('channelName');

        return setting($this->getSettingKey($channelName), '');;
    }

    public function getSettingKey($channelName): string
    {
        return 'answerOfferQuestion.' . strtolower($channelName);
    }
}
