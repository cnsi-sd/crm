<?php

namespace App\Console\Commands\ImportMessages;

use App\Helpers\EmailNormalized;
use App\Helpers\ImportMessages\Connector\AmenConnector;
use App\Helpers\ImportMessages\Connector\MicrosoftConnector;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use Exception;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use PhpImap\Exceptions\InvalidParameterException;

abstract class AbstractImportMailMessages extends AbstractImportMessages
{
    const SPAM_TAG = 'X-Spam-Tag';
    const SPAM_STATUS = 'X-Spam-Status';
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';

    const ALERT_LOCKED_SINCE = 600;
    const KILL_LOCKED_SINCE = 1200;

    /**
     * @var string
     */
    protected string $channelName;

    /**
     * @var boolean
     */
    protected bool $reverse;

    protected $connector;

    /**
     * @throws InvalidParameterException|IdentityProviderException
     * @throws Exception
     */
    protected function initApiClient(): void
    {
        $credentials = $this->getCredentials();
        $this->connector = match ($credentials['client']) {
            'amen' => new AmenConnector($credentials, $this->logger),
            'microsoft' => new MicrosoftConnector($credentials, $this->logger),
            default => throw new Exception("Invalid client"),
        };
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        $this->channel = Channel::getByName($this->channelName);
        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $from_time = strtotime(date('d M Y H:i:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date("d/m/Y", $from_time);

            $this->logger->info('--- Init api client ---');

            $this->initApiClient();

            $this->logger->info('--- Get Emails details ---');
            foreach ($this->connector->getEmails($from_date) as $emailId => $email) {
                try {
                    $this->logger->info('--- Email id: '. $emailId . '---');
                    $this->logger->info('Subject: ' . $email->getSubject());

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

                    $this->importEmail($email, $mpOrder);
                    $this->logger->info('Email imported');
                } catch (Exception $e) {
                    $this->logger->error('An error has occurred.', $e);
                    \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                    return;
                }
            }
        } catch (Exception $e){
            $this->logger->error('An error has occurred.', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }


    /**
     * @param EmailNormalized $email
     * @return bool
     * @throws Exception
     */
    protected function canImport(EmailNormalized $email): bool{

        $starter_date = $this->checkMessageDate($email->getDate());
        if (!$starter_date)
            return false;

        preg_match('/@(.*)/', $email->getSender(), $match);
        if (in_array($match[1], config('email-import.domain_blacklist'))) {
            $this->logger->info('Domaine blacklist');
            return false;
        }

        if (in_array($email->getSender(), config('email-import.email_blacklist'))) {
            $this->logger->info('Email blacklist');
            return false;
        }

        if ($this->isSpam($email)){
            if (in_array($match[1], config('email-import.domain_whitelist'))) {
                $this->logger->info('Domaine blacklist');
                return true;
            }
            if (in_array($match[1], config('email-import.email_whitelist'))) {
                $this->logger->info('Domaine blacklist');
                return true;
            }
            return false;
        }


        return true;
    }

    /**
     * @param EmailNormalized $email
     * @return bool|string
     */
    protected function parseOrderId(EmailNormalized $email): bool|string
    {
        return false;
    }

    /**
     * @param EmailNormalized $email
     * @return bool
     */
    protected function isSpam(EmailNormalized $email): bool
    {
        $spamSign = [self::SPAM_TAG, self::SPAM_STATUS];
        foreach ($spamSign as $spam){
            if (str_contains($spam, $email->getHeader())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param EmailNormalized $email
     * @param string $mpOrder
     * @return void
     */
    protected function importEmail(EmailNormalized $email, string $mpOrder): void {}

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param EmailNormalized $email
     * @return void
     * @throws Exception
     */
    protected function importMessageByThread(Ticket $ticket, Thread $thread, EmailNormalized $email): void
    {
        $this->logger->info('Check if this message is imported');
        if($this->isMessagesImported($email->getEmailId()))
            return;

        $this->logger->info('Convert email to message');
        $this->convertApiResponseToMessage($ticket, $email, $thread);
        $this->addImportedMessageChannelNumber($email->getEmailId());
    }
}
