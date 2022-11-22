<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketMessageAuthorType extends AbstractEnum
{
    const MESSAGE_ADMIN         = "ADMIN";
    const MESSAGE_OPERATEUR     = "OPERATEUR";
    const MESSAGE_CLIENT        = "CLIENT";
    const MESSAGE_SYSTEME       = "SYSTEME";
    const MESSAGE_API           = "API";
}
