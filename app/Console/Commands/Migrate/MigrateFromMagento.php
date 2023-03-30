<?php

namespace App\Console\Commands\Migrate;

use App\Console\Commands\Migrate\Steps\MigrateDefaultAnswer;
use App\Console\Commands\Migrate\Steps\MigrateRevival;
use App\Console\Commands\Migrate\Steps\MigrateSavNote;
use App\Console\Commands\Migrate\Steps\MigrateTag;
use App\Console\Commands\Migrate\Steps\MigrateUser;
use App\Console\Commands\Migrate\Tools\MigrateDTO;
use Cnsi\Logger\Logger;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Pipeline\Pipeline;
use PDO;

class MigrateFromMagento extends Command
{
    protected $signature = 'db:magento_init {dsn} {database} {username} {password}';
    protected $description = 'Fill CRM with Magento data';

    private array $steps = [
//        MigrateUser::class,
        MigrateDefaultAnswer::class,
        MigrateTag::class,
        MigrateRevival::class,
        MigrateSavNote::class,
    ];

    public function handle()
    {
        // Prevent unwanted execution
        $this->confirmExecution();

        // Build an object that will be sent through steps
        // DTO = Data Transfer Object
        $dto = new MigrateDTO(
            $this->getConnection(),
            $this->getLogger(),
        );

        // Disable foreign key constraints to be able to truncate tables
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // Run all steps
        app(Pipeline::class)
            ->send($dto)
            ->through(
                pipes: $this->steps,
            )
            ->thenReturn();

        // Enable back foreign key constraints
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }

    private function confirmExecution()
    {
        $response = $this->confirm('Attention, cette commande va supprimer des données pour en charger de nouvelles depuis Magento. Êtes-vous sûr de vouloir continuer ?');
        if (!$response) {
            die('Aborted');
        }
    }

    private function getConnection(): Connection
    {
        $pdo = new PDO(
            $this->argument('dsn'),
            $this->argument('username'),
            $this->argument('password'),
        );

        return new Connection($pdo, $this->argument('database'));
    }

    private function getLogger(): Logger
    {
        return new Logger('migrate/migrate.log', true);
    }
}
