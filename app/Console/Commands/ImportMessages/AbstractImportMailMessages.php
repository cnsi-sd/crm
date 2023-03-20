<?php

namespace App\Console\Commands\ImportMessages;

use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;

class AbstractImportMailMessages extends AbstractImportMessages
{

    protected function getCredentials(): array
    {
        // TODO: Implement getCredentials() method.
    }

    protected function initApiClient()
    {
        // TODO: Implement initApiClient() method.
    }

    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread)
    {
        // TODO: Implement convertApiResponseToMessage() method.
    }
}
