<?php

namespace Database\Seeders;

use Database\Seeders\dev\TagDatasetSeeder;
use Database\Seeders\dev\UserSeeder;
use Database\Seeders\dev\VariableSeeder;
use Database\Seeders\prod\ChannelSeeder;
use Database\Seeders\prod\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ChannelSeeder::class,
        ]);

        if(in_array(env('APP_ENV'), ['local', 'development', 'test', 'testing', 'staging'])) {
            $this->call([
                VariableSeeder::class,
                TagDatasetSeeder::class,
            ]);
        }
    }
}
