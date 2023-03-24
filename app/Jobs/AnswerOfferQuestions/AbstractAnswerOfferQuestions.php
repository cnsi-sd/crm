<?php

namespace App\Jobs\AnswerOfferQuestions;

use App\Models\Channel\Channel;
use Cnsi\Logger\Logger;
use Illuminate\Console\Command;

abstract class AbstractAnswerOfferQuestions extends Command
{
    protected Logger $logger;
    protected Channel $channel;
    abstract protected function getCredentials(): array;
    abstract protected function initApiClient();
}
