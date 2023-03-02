<?php

namespace Database\Seeders;

use Database\Seeders\dev\UserSeeder;
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
            ChannelSeeder::class,
        ]);
    }
}
