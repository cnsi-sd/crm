<?php

namespace App\Policies\Settings\Permissions;

//use App\Enums\PermissionEnum;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->isAdmin();
    }

    public function edit(User $user): bool
    {
        return $user->isAdmin();
    }
}
