<?php

namespace App\Policies\Configuration;

use App\Enums\PermissionEnum;
use App\Models\Channel\DefaultAnswer;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function PHPUnit\Framework\isNull;

class DefaultAnswerPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_READ);
    }

    public function edit(User $user, ?DefaultAnswer $defaultAnswer = null): bool
    {
        if(is_null($defaultAnswer) || !$defaultAnswer->is_locked)
            return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_EDIT);
        else if($defaultAnswer->is_locked)
            return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_EDIT)
                && $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_EDIT_LOCKED);
        else
            return false;
    }

    public function lock(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_LOCK);
    }

    public function editLocked(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::DEFAULT_ANSWER_EDIT_LOCKED);
    }
}
