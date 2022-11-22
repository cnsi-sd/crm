<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

class TicketCommentType extends AbstractEnum
{
    const OTHERS               = "others";
    const EXTERNAL_CONTACT       = "external contact";
    const RESPONSIBLE_INFO     = "responsible info";
    const PROCESS_TO_FOLLOW   = "process to follow";
    const INFO_IMPORTANT       = "info important";
    const SUMMARY          = "summary";
}
