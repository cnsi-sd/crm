<?php

namespace App\Listeners;

use App\Events\NewMessage;
use App\Models\Ticket\Message;

class SendShippingInformation extends AbstractNewMessageListener
{
    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();

        if(!$this->canBeProcessed())
            return self::SKIP;

        // TODO : Send Shipping Information

        return self::STOP_PROPAGATION;
    }

    protected function canBeProcessed(): bool
    {
        if(!$this->message->isExternal())
            return false;

        if(!$this->message->isFirstMessageOnThread())
            return false;

        if(!$this->matchPatterns($this->message))
            return false;

        return true;
    }

    protected function matchPatterns(Message $message): bool
    {
        $patterns = [
            "(Information sur la livraison|Article non reçu)", // Mirakl
            "(Je n'ai pas reçu mon colis|Ma commande est expédiée, mais je ne l'ai pas reçue)", // Cdiscount
            "(Avez-vous expédié l'article ?)", // Rakuten
            "(Demande de renseignements concernant la livraison d'une commande)", // Amazon
        ];

        $threadSubject = $message->thread->name;
        foreach($patterns as $pattern) {
            if(preg_match($pattern, $threadSubject, $matches)) {
                return true;
            }
        }

        return false;
    }
}
