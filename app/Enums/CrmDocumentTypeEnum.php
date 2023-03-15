<?php

namespace App\Enums;

use Cnsi\Attachments\Enum\DocumentTypeEnum;

class CrmDocumentTypeEnum extends DocumentTypeEnum
{
    const CUSTOMER_SERVICE_REPORT = "customer_service_report";
    const CLIENT_BANK_ACCOUNT_NUMBER = "client_bank_account_number";
    const CUSTOMER_SERVICE_STATION = "customer_service_station";
    const PRODUCT_PHOTO = "product_photo";
    const CUSTOMER_FILING = "customer_filing";
    const OTHER = "other";
}
