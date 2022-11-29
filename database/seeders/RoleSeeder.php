<?php

namespace Database\Seeders;

use App\Enums\Role\RoleEnum;
use App\Models\User\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(){
        foreach (RoleEnum::getList() as $value)
            Role::firstOrCreate(['name' => $value]);
    }
}
