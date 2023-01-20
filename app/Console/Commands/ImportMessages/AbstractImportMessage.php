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

    protected static $_alreadyImportedMessages;

    protected $signature = '%s:import:messages {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
    protected $description = 'Importing messages from Marketplace.';
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
