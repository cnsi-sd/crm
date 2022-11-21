<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketMessageAuthorType extends AbstractEnum
{
    const MESSAGE_ADMIN         = "admin";
    const MESSAGE_OPERATEUR     = "operateur";
    const MESSAGE_CLIENT        = "client";
    const MESSAGE_SYSTEME       = "systeme";
    const MESSAGE_API           = "api";
}
