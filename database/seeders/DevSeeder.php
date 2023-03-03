<?php

namespace Database\Seeders;

use Database\Seeders\dev\UserSeeder;
use Database\Seeders\prod\RoleSeeder;
use Exception;

class DevSeeder extends DatabaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if(!in_array(env('APP_ENV'), ['local', 'development', 'test', 'testing', 'staging'])) {
            throw new Exception(sprintf('The DevSeeder cannot be used in production. "%s" environment given', env('APP_ENV')));
        }

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        parent::run();
    }
}
