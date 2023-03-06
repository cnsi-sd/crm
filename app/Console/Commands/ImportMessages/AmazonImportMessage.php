<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use PhpImap\Exceptions\ConnectionException;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox;

class AmazonImportMessage extends AbstractImportMessages
{
    /** @var Mailbox */
    private Mailbox $mailbox;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'amazon');
        //$this->FROM_SHOP_TYPE = 'Seller';
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(){
        $this->channel = Channel::getByName(ChannelEnum::AMAZON_FR);
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
                'SUBJECT' => 'Demande de renseignements',
                'SINCE' => $from_date
            ]);

            $this->logger->info('--- Get Emails details');
            foreach($this->getEmails($emailIds) as $emailId => $email){
                dd($email->textHtml);
            }

            $t = "kqdjfv";

        } catch (Exception $e){
            $this->logger->error('An error has occurred. Rolling back.', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }
    protected function getCredentials(): array
    {
        return [
            'API_URL'            => env('AMAZON_API_URL'),
            'API_USERNAME'       => env('AMAZON_USERNAME'),
            'API_PASSWORD'       => env('AMAZON_PASSWORD')
        ];
    }

    /**
     * @throws InvalidParameterException
     */
    protected function initApiClient()
    {
        $credentials = $this->getCredentials();
        $this->mailbox = new Mailbox(
            '{'. $credentials['API_URL'].':993/imap/ssl/novalidate-cert}INBOX',
            $credentials['API_USERNAME'],
            $credentials['API_PASSWORD']
        );
    }

    private function search($query = []): array
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

    private function getEmails($emailIds): array
    {
        $emails = [];
        foreach ($emailIds as $emailId) {
            $this->logger->info('--- Get Email : '. $emailId);
            $emails[$emailId] = $this->mailbox->getMail($emailId,false);
            break;
        }
        return $emails;
    }

    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread)
    {
        // TODO: Implement convertApiResponseToMessage() method.
    }
}
