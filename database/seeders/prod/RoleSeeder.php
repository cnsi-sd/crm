<?php

namespace Database\Seeders\prod;

use App\Enums\PermissionEnum;
use App\Models\User\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(){
        /** @var Role $admin_role */
        $admin_role = Role::firstOrNew([
            'name' => 'admin',
        ]);
        $admin_role->permissions = implode(';', PermissionEnum::getList());
        $admin_role->save();
    }
}
