<?php

namespace App\Policies\Configuration\Permission;

use App\Enums\PermissionEnum;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevivalPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::REVIVAL_READ);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::REVIVAL_EDIT);
    }
}
