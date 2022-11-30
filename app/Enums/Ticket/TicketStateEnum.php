<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketStateEnum extends AbstractEnum
{
    const WAITING_CUSTOMER    = "waiting_customer";
    const WAITING_ADMIN       = "waiting_admin";
    const CLOSED              = "closed";
}
