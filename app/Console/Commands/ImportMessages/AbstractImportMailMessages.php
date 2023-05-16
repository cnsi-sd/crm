<?php

namespace App\Console\Commands\ImportMessages;

use App\Console\Commands\ImportMessages\Connector\AmenConnector;
use App\Console\Commands\ImportMessages\Connector\MicrosoftConnector;
use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;
use Webklex\PHPIMAP\ClientManager;

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
            default => throw new \Exception("Invalid client"),
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
            $from_date = date("d M Y H:i:s", $from_time);

            $this->logger->info('--- Init api client ---');

            $this->initApiClient();

            $this->logger->info('--- Init filters ---');


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
     * @param $email
     * @return bool
     * @throws Exception
     */
    protected function canImport($email): bool{

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
            if (str_contains($spam, $email->getHeader())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $email
     * @param string $mpOrder
     * @return void
     */
    protected function importEmail($email, string $mpOrder): void {}

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param $email
     * @return void
     * @throws Exception
     */
    protected function importMessageByThread(Ticket $ticket, Thread $thread, $email): void
    {
        $this->logger->info('Check if this message is imported');
        if($this->isMessagesImported($email->getEmailId()))
            return;

        $this->logger->info('Convert email to message');
        $this->convertApiResponseToMessage($ticket, $email, $thread);
        $this->addImportedMessageChannelNumber($email->getEmailId());
    }
}
