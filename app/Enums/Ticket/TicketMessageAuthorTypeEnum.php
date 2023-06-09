<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

abstract class TicketMessageAuthorTypeEnum extends AbstractEnum
{
    const ADMIN         = "admin";
    const OPERATOR      = "operator";
    const CUSTOMER      = "customer";
    const SYSTEM        = "system";
    const API           = "api";
}
