<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
//         User::factory(10)->create();

         User::firstOrCreate(
             [ 'email' => 'johndoe@cnsi-sd.fr'],
             [
                 'name' => 'John Doe',
                 'password' => Hash::make('password'),
                 'active' => true,
                 'role_id' => 1,
             ]
         );
    }
}
