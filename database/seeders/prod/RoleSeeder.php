<?php

namespace Database\Seeders\prod;

use App\Enums\PermissionEnum;
use App\Models\User\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(){
        /** @var Role $role */
        $role = Role::firstOrNew([
            'name' => 'Admin',
        ]);
        $role->permissions = implode(';', PermissionEnum::getList());
        $role->save();

        /** @var Role $role */
        $role = Role::firstOrCreate([
            'name' => 'ADV',
        ]);
    }
}
