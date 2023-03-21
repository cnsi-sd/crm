<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;

abstract class AbstractImportMailMessages extends AbstractImportMessages
{
    const SPAM_TAG = 'X-Spam-Tag';
    const SPAM_STATUS = 'X-Spam-Status';
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';

    /**
     * @var Mailbox
     */
    protected Mailbox $mailbox;
    /**
     * @var string
     */
    protected string $channelName;
    /**
     * @var boolean
     */
    protected bool $reverse;

    /**
     * @var string
     */
    protected string $mailHost;
    /**
     * @var string
     */
    protected string $mailUsername;
    /**
     * @var string
     */
    protected string $mailPassword;
    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'host' => $this->mailHost,
            'username' => $this->mailUsername,
            'password' => $this->mailPassword
        ];
    }

    /**
     * @throws InvalidParameterException
     */
    protected function initApiClient()
    {
        $credentials = $this->getCredentials();
        $this->mailbox = new Mailbox(
            '{'. $credentials['host'].':993/imap/ssl/novalidate-cert}INBOX',
            $credentials['username'],
            $credentials['password']
        );
    }

    /**
     * @throws Exception
     */
    public function handle(){
        $this->channel = Channel::getByName($this->channelName);
        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $from_time = strtotime(date('d M Y H:i:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date("d M Y H:i:s", $from_time);

            $this->logger->info('--- Init api client ---');

            $this->initApiClient();

            $this->logger->info('--- Init filters ---');
            $emailIds = $this->search([
                'SINCE' => $from_date
            ]);

            $this->logger->info('--- Get Emails details');
            foreach ($this->getEmails($emailIds, $this->reverse) as $emailId => $email) {
                try {
                    $this->logger->info($emailId);
                    $this->logger->info('Subject: ' . $email->subject);

                    // Check can import mail
                    if (!$this->canImport($email)) {
                        $this->logger->info('cannot import email');
                        continue;
                    }

                    $this->logger->info('Retrieve command number from email');
                    $mpOrder = $this->parseOrderId($email);
                    if (!$mpOrder) {
                        $this->logger->error('marketplace order id not found');
                        continue;
                    }

                    $this->logger->info('Begin Transaction');
                    DB::beginTransaction();
                    $this->importEmail($email, $mpOrder);
                    $this->logger->info('Email imported');
                    DB::commit();
                } catch (Exception $e) {
                    $this->logger->error('An error has occurred. Rolling back.', $e);
                    DB::rollBack();
                    \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                    return;
                }
            }
        } catch (Exception $e){
            $this->logger->error('An error has occurred. Rolling back.', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }

    /**
     * @param array $query
     * @return int[]
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

    /**
     * @param int[] $emailIds
     * @param bool $reverse
     * @return array
     */
    protected function getEmails(array $emailIds, bool $reverse = false): array
    {
        $emails = [];
        foreach ($emailIds as $emailId) {
            $this->logger->info('Get Email : '. $emailId);
            $emails[$emailId] = $this->mailbox->getMail($emailId,false);
        }

        return $reverse ? array_reverse($emails) : $emails;
    }

    /**
     * @param $email
     * @return bool
     */
    protected function canImport($email): bool{

        if (in_array($email->senderAddress, config('email-import.domain_blacklist')))
            return false;

        if (in_array($email->senderAddress, config('email-import.email_blacklist')))
            return false;

        if (!$this->isSpam($email))
            return false;

        return true;
    }

    /**
     * @param $email
     * @return bool|string
     */
    protected function parseOrderId($email): bool|string
    {
        return false;
    }

    /**
     * @param $email
     * @return bool
     */
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

    /**
     * @param IncomingMail $email
     * @param string $mpOrder
     * @return void
     */
    protected function importEmail(IncomingMail $email, string $mpOrder): void {}

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param IncomingMail $email
     * @return void
     * @throws Exception
     */
    protected function importMessageByThread(Ticket $ticket, Thread $thread, IncomingMail $email): void
    {
        $this->logger->info('Check if this message is imported');
        if($this->isMessagesImported($email->messageId))
            return;

        $this->logger->info('Convert api message to db message');
        $this->convertApiResponseToMessage($ticket, $email, $thread);
        $this->addImportedMessageChannelNumber($email->id);
    }
}
