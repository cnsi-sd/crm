<?php

namespace App\Listeners;

use App\Events\NewMessage;

class SendInvoice extends AbstractNewMessageListener
{
    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();

        if(!$this->canBeProcessed())
            return self::SKIP;

        // TODO : try to get invoice
        // TODO : if invoice : send invoice to customer, close ticket
        // TODO : if no invoice and order not shipped : send invoice will we available, close ticket
        // TODO : if no invoice and order has been shipped, return self::SKIP

        return self::STOP_PROPAGATION;
    }

    protected function canBeProcessed(): bool
    {
        if (!setting('bot.invoice.active'))
            return false;

        if (!$this->message->isExternal())
            return false;

        if (!$this->message->isFirstMessageOnThread())
            return false;

        if (!preg_match('/(facture)/i', $this->message->content, $matches))
            return false;

        return true;
    }
}
