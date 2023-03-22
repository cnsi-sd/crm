<?php

namespace App\Policies\Configuration;

use App\Enums\PermissionEnum;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SavNotePolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::SAV_NOTE_READ);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::SAV_NOTE_EDIT);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::SAV_NOTE_DELETE);
    }
}
