<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

class TicketComment extends AbstractEnum
{
    const COMMENT_TYPE_DIVERS               = "DIVERS";
    const COMMENT_TYPE_CONTACT_EXTENE       = "CONTACT EXTERNE";
    const COMMENT_TYPE_INFO_RESPONSABLE     = "INFO RESPONSABLE";
    const COMMENT_TYPE_PROCEDURE_A_SUIVRE   = "PROCEDURE À SUIVRE";
    const COMMENT_TYPE_INFO_IMPORTANT       = "INFO IMPORTANT";
    const COMMENT_TYPE_RESUME               = "RÉSUMÉ";
}
