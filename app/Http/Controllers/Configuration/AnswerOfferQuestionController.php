<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\AbstractController;
use App\Models\Channel\DefaultAnswer;
use Illuminate\Http\Request;


class AnswerOfferQuestionController extends AbstractController
{
    public function getMessageContent(Request $request)
    {
        $channelName = $request->input('channelName');
        $defaultAnswerId = setting($channelName . '.defaultAnswerOfferQuestion');
        $defaultAnswer = DefaultAnswer::find($defaultAnswerId);
        if ($defaultAnswer)
            return $defaultAnswer->content;
        else
            return null;
    }
}
