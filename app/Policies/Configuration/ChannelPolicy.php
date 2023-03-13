<?php

namespace App\Policies\Configuration;

use App\Enums\PermissionEnum;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChannelPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::CHANNEL_READ);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::CHANNEL_EDIT);
    }
}
