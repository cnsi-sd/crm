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

    public function getColor(string $params){
        switch ($params){
            case(self::OTHERS):
                return 'gray';
                break;
            case(self::EXTERNAL_CONTACT):
                return 'green';
                break;
            case(self::RESPONSIBLE_INFO):
                return 'purple';
                break;
            case(self::PROCESS_TO_FOLLOW):
                return 'blue';
                break;
            case(self::INFO_IMPORTANT):
                return 'red';
                break;
            case(self::SUMMARY):
                return 'orange';
                break;
        }
    }
}
