<?php

namespace App\Enums\Ticket;

use App\Enums\AbstractEnum;

class TicketCommentTypeEnum extends AbstractEnum
{
    const OTHERS            = "others";
    const EXTERNAL_CONTACT  = "external_contact";
    const RESPONSIBLE_INFO  = "responsible_info";
    const PROCESS_TO_FOLLOW = "process_to_follow";
    const INFO_IMPORTANT    = "info_important";
    const SUMMARY           = "summary";

    public function getColor(string $params)
    {
        return match ($params)
            {
                self::OTHERS            => 'gray',
                self::EXTERNAL_CONTACT  => 'green',
                self::RESPONSIBLE_INFO  => 'purple',
                self::PROCESS_TO_FOLLOW => 'blue',
                self::INFO_IMPORTANT    => 'red',
                self::SUMMARY           => 'orange'
            };
    }
}
