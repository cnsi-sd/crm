<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketState extends AbstractEnum
{
    const STATE_ATTENTE_CLIENT      = "ATTENTE CLIENT";
    const STATE_ATTENTE_ADMIN       = "ATTENTE ADMIN";
    const STATE_FERME               = "FERMÉE";
}
