<?php

namespace Database\Seeders\dev;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::firstOrCreate(
             [ 'email' => 'john@cnsi-sd.fr'],
             [
                 'name' => 'John Doe',
                 'password' => Hash::make('password'),
                 'active' => true,
                 'role_id' => 1,
             ]
         );
    }
}
