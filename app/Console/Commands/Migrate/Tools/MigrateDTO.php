<?php

namespace App\Console\Commands\Migrate\Tools;

use Cnsi\Logger\Logger;
use Illuminate\Database\Connection;

class MigrateDTO
{
    public function __construct(
        public Connection $connection,
        public Logger     $logger,
    )
    {
    }
}
