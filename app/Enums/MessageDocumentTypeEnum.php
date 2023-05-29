<?php

namespace App\Enums;

use Cnsi\Attachments\Enum\DocumentTypeEnum;

class MessageDocumentTypeEnum extends DocumentTypeEnum
{
    const AUTHENTIC_CERTIFICATE = "authentic_certificate"; // confo
    const CUSTOMER_INVOICE = "customer_invoice"; // confo, ubaldi, but, leclerc,metro ,boulanger, darty, fnac
    const CUSTOMER_RETURN = "customer_return"; // ubaldi, darty, fnac
    const CUSTOMER_UPLOAD = "customer_upload"; // but, leclerc
    const MANUAL_USE = "manual_use"; // confo, darty, fnac
    const PREPAID_RETURN_TICKET = "prepaid_return_ticket"; // metro
    const OTHER = "other"; // tous
    const SLIP_RETURN = "slip_return"; // darty
    const TYPE_RETURN_CONDITIONS = "return_conditions"; // darty, fnac
    const TYPE_PHOTO = "photo"; // darty, fnac
    const TYPE_PROTEST_DOC = "protest_doc"; // darty, fnac
}
