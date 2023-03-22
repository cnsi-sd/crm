@switch($ticket->channel->name)
    @case("darty.com")
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_INVOICE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_INVOICE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::SLIP_RETURN }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('SLIP_RETURN') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
        @break
    @case("conforama.fr")
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_INVOICE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_INVOICE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::AUTHENTIC_CERTIFICATE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('AUTHENTIC_CERTIFICATE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::MANUAL_USE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('MANUAL_USE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
        @break
    @case("but.fr")
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_INVOICE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_INVOICE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_UPLOAD }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_UPLOAD') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
        @break
    @case("e.leclerc")
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_INVOICE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_INVOICE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_UPLOAD }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_UPLOAD') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
        @break
    @case("metro.fr")
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_INVOICE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_INVOICE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::PREPAID_RETURN_TICKET }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('PREPAID_RETURN_TICKET') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
        @break
    @case("ubaldi")
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_INVOICE }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_INVOICE') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::CUSTOMER_RETURN }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('CUSTOMER_RETURN') }}
        </option>
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
        @break
    @default
        <option value="{{ \App\Enums\MessageDocumentTypeEnum::OTHER }}">
            {{ \App\Enums\MessageDocumentTypeEnum::getMessage('OTHER') }}
        </option>
@endswitch
