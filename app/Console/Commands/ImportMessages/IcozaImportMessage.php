<?php

namespace App\Console\Commands\ImportMessages;

use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use FnacApiClient\Entity\Message;
use GuzzleHttp\Client;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;

class IcozaImportMessage extends AbstractImportMessage
{

    protected function getChannelName(): string
    {
        // TODO: Implement getChannelName() method.
    }

    protected function getCredentials(): array
    {
        // TODO: Implement getCredentials() method.
    }

    protected function getMessageApiId(ThreadMessage|Message $message): string
    {
        // TODO: Implement getMessageApiId() method.
    }

    protected function getMpOrderApiId($message, $thread = null)
    {
        // TODO: Implement getMpOrderApiId() method.
    }

    protected function convertApiResponseToMessage(Ticket $ticket, $message, Thread $thread)
    {
        // TODO: Implement convertApiResponseToMessage() method.
    }

    public function __construct()
    {
        $this->signature =sprintf($this->signature,'icoza');
        return parent::__construct();
    }

    protected Logger $logger;
    static private Client $client = null;
}
