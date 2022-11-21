<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketState extends AbstractEnum
{
    const STATE_ATTENTE_CLIENT      = "attente client";
    const STATE_ATTENTE_ADMIN       = "attente admin";
    const STATE_FERME               = "fermé";
}
