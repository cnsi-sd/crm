<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\User\Role;
use App\Models\User\User;
use Closure;

class MigrateUser extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $role = Role::firstWhere('name', 'ADV');
        $data = $dto->connection->select('
            SELECT firstname, lastname, email
            FROM admin_user
            WHERE is_active=1
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);
        foreach ($data as &$user) {
            $user['role_id'] = $role->id;
            $user['active'] = 1;
        }

        User::truncate();
        User::insert($data);

        $dto->logger->info(count($data) . ' Users inserted');

        return $next($dto);
    }
}
