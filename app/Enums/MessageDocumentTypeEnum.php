<?php

namespace App\Enums;

use Cnsi\Attachments\Enum\DocumentTypeEnum;

class MessageDocumentTypeEnum extends DocumentTypeEnum
{
    const AUTHENTIC_CERTIFICATE = "authentic_certificate"; // confo
    const CUSTOMER_INVOICE = "customer_invoice"; // confo, darty, ubaldi, but, leclerc,metro ,boulanger
    const CUSTOMER_RETURN = "customer_return"; // ubaldi
    const CUSTOMER_UPLOAD = "customer_upload"; // but, leclerc
    const MANUAL_USE = "manual_use"; // confo
    const PREPAID_RETURN_TICKET = "prepaid_return_ticket"; // metro
    const OTHER = "other"; // tous
    const SLIP_RETURN = "slip_return"; // darty
}
