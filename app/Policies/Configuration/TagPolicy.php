<?php

namespace App\Policies\Configuration;

use App\Enums\PermissionEnum;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::TAG_READ);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::TAG_EDIT);
    }

    public function lock(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::TAG_LOCK);
    }

    public function editLocked(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::TAG_EDIT_LOCKED);
    }
}
