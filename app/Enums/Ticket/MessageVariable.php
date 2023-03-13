<?php

namespace App\Enums\Ticket;

use App\Models\Channel\Order;
use App\Models\Ticket\Message;

enum MessageVariable: string
{
    // Order scope
    case PRENOM_CLIENT = 'Prénom client';
    case NOM_CLIENT = 'Nom client';
    case URL_SUIVI = 'URL Suivi';
    case DELAI_COMMANDE = 'Délai commande';
    case MARKETPLACE = 'Marketplace';
    case NUM_CMD_MP = 'Numéro commande MP';

    // User scope
    case SIGNATURE_ADMIN = 'Signature admin';

    // Global scope
    case SIGNATURE_BOT = 'Signature bot';
    case NOM_BOUTIQUE = 'Nom boutique';
    case TELEPHONE_BOUTIQUE = 'Téléphone boutique';

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
        $order = $message->thread->ticket->order;
        $extOrder = $order->getFirstPrestashopOrder();

        return match($this)
        {
            self::PRENOM_CLIENT => $extOrder['invoice_address']['firstname'],
            self::NOM_CLIENT => $extOrder['invoice_address']['lastname'],
            self::URL_SUIVI => $extOrder['shipping']['url'],
            self::DELAI_COMMANDE => Order::getOrderDelay($extOrder),
            self::MARKETPLACE => ucfirst($order->channel->name),
            self::NUM_CMD_MP => $order->channel_order_number,
            self::SIGNATURE_ADMIN => $message->user->name,
            self::SIGNATURE_BOT, self::NOM_BOUTIQUE, self::TELEPHONE_BOUTIQUE  => $this->getSettingValue(),
        };
    }

    public function isConfigurable(): bool
    {
        return match($this) {
            self::SIGNATURE_BOT, self::NOM_BOUTIQUE, self::TELEPHONE_BOUTIQUE => true,
            default => false,
        };
    }

    public function getSettingKey(): string
    {
        return 'variables.' . strtolower($this->name);
    }

    public function getSettingValue(): string
    {
        return setting($this->getSettingKey(), '');
    }
}
