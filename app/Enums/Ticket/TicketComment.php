<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

class TicketComment extends AbstractEnum
{
    const COMMENT_TYPE_DIVERS               = "divers";
    const COMMENT_TYPE_CONTACT_EXTENE       = "Contact externe";
    const COMMENT_TYPE_INFO_RESPONSABLE     = "info responsable";
    const COMMENT_TYPE_PROCEDURE_A_SUIVRE   = "procedure à suivre";
    const COMMENT_TYPE_INFO_IMPORTANT       = "info important";
    const COMMENT_TYPE_RESUME               = "résumé";
}
