<?php

namespace App\Enums\Ticket;

use App\Models\Channel\Order;
use App\Models\Ticket\Message;

enum MessageVariable
{
    case EXT_ORDER_CUSTOMER_FIRSTNAME;
    case EXT_ORDER_CUSTOMER_LASTNAME;
    case EXT_ORDER_TRACKING_URL;
    case EXT_ORDER_DELAY;

    case BOT_NAME;

    public function templateVar(): string
    {
        return '{{' . $this->name . '}}';
    }

    public function getValue(Message $message): string
    {
        $extOrder = $message->thread->ticket->order->getFirstPrestashopOrder();

        return match($this)
        {
            MessageVariable::EXT_ORDER_CUSTOMER_FIRSTNAME => $extOrder['invoice_address']['firstname'],
            MessageVariable::EXT_ORDER_CUSTOMER_LASTNAME => $extOrder['invoice_address']['lastname'],
            MessageVariable::EXT_ORDER_TRACKING_URL => $extOrder['shipping']['url'],
            MessageVariable::EXT_ORDER_DELAY => Order::getOrderDelay($extOrder),
            MessageVariable::BOT_NAME => 'Olympe',
        };
    }
}
