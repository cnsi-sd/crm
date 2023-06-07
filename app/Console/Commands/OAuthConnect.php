<?php

namespace App\Console\Commands;

use App\Helpers\ImportMessages\Connector\MicrosoftConnector;
use Illuminate\Console\Command;

class OAuthConnect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:connect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info(MicrosoftConnector::getConnectionLink());

    }
}
