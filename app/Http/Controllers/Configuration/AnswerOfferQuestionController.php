<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\AbstractController;
use App\Models\Channel\Channel;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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
