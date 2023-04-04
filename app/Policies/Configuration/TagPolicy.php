<?php

namespace App\Policies\Configuration;

use App\Enums\PermissionEnum;
use App\Models\Tags\Tag;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::TAG_READ);
    }

    public function edit(User $user, ?Tag $tag = null): bool
    {
        if(is_null($tag) || !$tag->is_locked)
            return $user->hasPermission(PermissionEnum::TAG_EDIT);
        else if($tag->is_locked)
            return $user->hasPermission(PermissionEnum::TAG_EDIT)
                && $user->hasPermission(PermissionEnum::TAG_EDIT_LOCKED);
        else
            return false;
    }

    public function lock(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::TAG_LOCK);
    }
}
