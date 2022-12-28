<?php

namespace App\Policies\Tickets;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function read(User $user): bool
    {
        return $user->isAdmin();
    }
}
