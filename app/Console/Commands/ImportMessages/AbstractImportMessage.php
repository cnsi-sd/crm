<?php

namespace App\Console\Commands\ImportMessages;

use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Console\Command;

abstract class AbstractImportMessage extends Command
{
    protected Logger $logger;
    protected string $log_path;
    const FROM_DATE_TRANSFORMATOR = ' -  2 hours';
    const HTTP_CONNECT_TIMEOUT = 15;
    protected static $_alreadyImportedMessages;

    //TODO redefine signature and description

    abstract protected function getChannelName(): string;
    abstract protected function getCredentials(): array;

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->logger = new Logger('import_message/' . $this->getChannelName() . '/' . $this->getChannelName() . '.log', true, true);
        $this->logger->info('--- Start ---');

        $date_time = new DateTime();
        $date_time->modify(self::FROM_DATE_TRANSFORMATOR);


//        $request->setWithMessages(true);
    }
}
