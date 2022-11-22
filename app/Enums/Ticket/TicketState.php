<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketState extends AbstractEnum
{
    const WAIT_CUSTOMER     = "wait customer";
    const WAIT_ADMIN       = "wait admin";
    const CLOSE               = "close";
}
