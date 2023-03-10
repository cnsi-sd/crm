<?php

namespace App\Enums\Ticket;

use App\Models\Channel\Order;
use App\Models\Ticket\Message;

enum MessageVariable: string
{
    case PRENOM_CLIENT = 'Prénom cient';
    case NOM_CLIENT = 'Nom cient';
    case URL_SUIVI = 'URL Suivi';
    case DELAI_COMMANDE = 'Délai commande';

    case SIGNATURE_BOT = 'Signature bot';

    public function templateVar(): string
    {
        return '{' . $this->name . '}';
    }

    public static function getTinyMceVariables(): array
    {
        $variables = [];

        foreach(MessageVariable::cases() as $variable) {
            $variables[] = [
                'text' => $variable->value,
                'value' => $variable->templateVar(),
            ];
        }

        return $variables;
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
