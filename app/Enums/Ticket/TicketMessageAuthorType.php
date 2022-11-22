<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketMessageAuthorType extends AbstractEnum
{
    const ADMIN         = "admin";
    const OPERATEUR     = "operator";
    const CUSTOMER        = "customer";
    const SYSTEM       = "system";
    const API           = "api";
}
