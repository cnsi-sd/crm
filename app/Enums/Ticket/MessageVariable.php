<?php

namespace App\Enums\Ticket;

use App\Models\Channel\Order;
use App\Models\Ticket\Message;

enum MessageVariable
{
    case PRENOM_CLIENT;
    case NOM_CLIENT;
    case URL_SUIVI;
    case DELAI_COMMANDE;

    case SIGNATURE_BOT;

    public function templateVar(): string
    {
        return '{' . $this->name . '}';
    }

    public function getValue(Message $message): string
    {
        $extOrder = $message->thread->ticket->order->getFirstPrestashopOrder();

        return match($this)
        {
            MessageVariable::PRENOM_CLIENT => $extOrder['invoice_address']['firstname'],
            MessageVariable::NOM_CLIENT => $extOrder['invoice_address']['lastname'],
            MessageVariable::URL_SUIVI => $extOrder['shipping']['url'],
            MessageVariable::DELAI_COMMANDE => Order::getOrderDelay($extOrder),
            MessageVariable::SIGNATURE_BOT => 'Olympe',
        };
    }
}
