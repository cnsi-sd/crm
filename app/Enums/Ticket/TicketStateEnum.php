<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketStateEnum extends AbstractEnum
{
    const OPENED = "opened";
    const CLOSED = "closed";
}
