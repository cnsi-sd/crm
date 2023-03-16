<?php

namespace App\Enums;

use Cnsi\Attachments\Enum\DocumentTypeEnum;

class MessageDocumentTypeEnum extends DocumentTypeEnum
{
    const AUTHENTIC_CERTIFICATE = "authentic_certificate"; // confo
    const CUSTOMER_INVOICE = "customer_invoice"; // confo, darty, ubaldi
    const CUSTOMER_RETURN = "customer_return"; // ubaldi
    const MANUAL_USE = "manual_use"; // confo
    const OTHER = "other"; // tous
    const SLIP_RETURN = "slip_return"; // darty
}
