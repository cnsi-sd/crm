<?php

namespace App\Listeners;

use App\Events\NewMessage;

class SendInvoice extends AbstractListener
{
    public function handle(NewMessage $event): ?bool
    {
        if(!setting('bot.invoice.active'))
            return self::SKIP;

        $message = $event->getMessage();

        if(!$message->isExternal())
            return self::SKIP;

        if(!$message->isFirstMessageOnThread())
            return self::SKIP;

        if(!preg_match('/(facture)/i', $message->content, $matches))
            return self::SKIP;

        // TODO : try to get invoice
        // TODO : if invoice : send invoice to customer, close ticket
        // TODO : if no invoice and order not shipped : send invoice will we available, close ticket
        // TODO : if no invoice and order has been shipped, return self::SKIP

        return self::STOP_PROPAGATION;
    }
}
