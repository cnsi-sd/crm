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

    protected function search($query = []): array
    {
        if(empty($query)) {
            $query = ['All' => null];
        }

        $criterias = [];
        foreach($query as $criteria => $value) {
            if(empty($value)) {
                $criterias[] = strtoupper($criteria);
                continue;
            }
            $criterias[] = strtoupper($criteria).' "'.$value.'"';
        }

        return $this->mailbox->searchMailbox(implode(' ', $criterias));
    }

    protected function getEmails($emailIds): array
    {
        $emails = [];
        foreach ($emailIds as $emailId) {
            $this->logger->info('Get Email : '. $emailId);
            $emails[$emailId] = $this->mailbox->getMail($emailId,false);
        }
        return $emails;
    }
}
