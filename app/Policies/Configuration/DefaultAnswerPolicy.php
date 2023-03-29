<?php

namespace App\Policies\Configuration;

use App\Enums\PermissionEnum;
use App\Models\Channel\DefaultAnswer;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultAnswerPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_READ);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_EDIT);
    }

    public function lock(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_LOCK);
    }

    public function editLocked(User $user, DefaultAnswer $defaultAnswer): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_EDIT_LOCKED);
    }
}
