<?php

namespace App\Console\Commands\ImportMessages\Connector;

use _PHPStan_a3459023a\Nette\Utils\DateTime;
use App\Helpers\EmailAttachementNormalized;
use App\Helpers\EmailNormalized;
use App\Helpers\TmpFile;
use Cnsi\Logger\Logger;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox;

/**
 *
 */
class AmenConnector
{

    protected $credentials;

    protected Logger $logger;

    /**
     * @var Mailbox
     */
    protected Mailbox $mailbox;

    /**
     * @param $credentials
     * @param $logger
     * @throws InvalidParameterException
     */
    public function __construct($credentials, $logger)
    {
        $this->credentials = $credentials;
        $this->amenConnection();
        $this->logger = $logger;
    }

    /**
     * @throws InvalidParameterException
     */
    public function amenConnection(): void
    {
        $this->mailbox = new Mailbox(
            '{'. $this->credentials['host'].':993/imap/ssl/novalidate-cert}INBOX',
            $this->credentials['username'],
            $this->credentials['password']
        );
    }

    /**
     * @throws \Exception
     * @return EmailNormalized[]
     */
    public function getEmails($from_date): array
    {
        $emailIds = $this->search([
            'SINCE' => $from_date,
        ]);

        $emails = [];
        foreach ($emailIds as $emailId) {
            $this->logger->info('Get Email : '. $emailId);
            $email = $this->mailbox->getMail($emailId,false);
            $listAttachment = [];
            if ($email->hasAttachments()) {
                foreach ($email->getAttachments() as $attachment) {
                    $tmpFile = new TmpFile((string) $attachment->getContents());
                    $listAttachment[] = new EmailAttachementNormalized($attachment->name, $tmpFile);
                }
            }
            $emails[$emailId] = new EmailNormalized(
                $email->id,
                new DateTime($email->date),
                $email->senderAddress,
                $email->headersRaw,
                $email->fromAddress,
                $email->subject,
                $email->hasAttachments(),
                $listAttachment,
                $email->textHtml,
                $email->textPlain
            );
        }


        return $emails;
    }

    /**
     * @param array $query
     * @return array
     */
    protected function search(array $query = []): array
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
}
