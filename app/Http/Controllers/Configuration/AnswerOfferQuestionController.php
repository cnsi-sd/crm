<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\AbstractController;
use Illuminate\Contracts\View\View;

class AnswerOfferQuestionController extends AbstractController
{
    public function home(): View
    {
        return view('configuration.answerOfferQuestion.home');
    }
}
