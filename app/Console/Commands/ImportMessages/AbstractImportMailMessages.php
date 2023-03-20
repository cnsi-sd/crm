<?php

namespace App\Console\Commands\ImportMessages;

use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;

abstract class AbstractImportMailMessages extends AbstractImportMessages
{
    const SPAM_TAG = 'X-Spam-Tag';
    const SPAM_STATUS = 'X-Spam-Status';

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

    protected function parseOrderId($email): bool|string{}

    protected function canImport($email): bool{

        if(in_array($email->senderAddress, config('email-import.domain_blacklist')))
            return false;
        if(in_array($email->senderAddress, config('email-import.email_blacklist')))
            return false;
        if (!$this->isSpam($email))
            return false;
        return true;
    }

    protected function isSpam($email): bool
    {
        $spamSign = [self::SPAM_TAG, self::SPAM_STATUS];
        foreach ($spamSign as $spam){
            if (str_contains($spam, $email->headersRaw)) {
                return true;
            }
        }
        return false;
    }

    protected function getSpecificActions($email){}

}
