<?php

namespace App\Listeners;

use App\Events\NewMessage;
use App\Models\Ticket\Message;

class SendShippingInformation extends AbstractListener
{
    public function handle(NewMessage $event): ?bool
    {
        $message = $event->getMessage();

        if(!$message->isExternal())
            return self::SKIP;

        if(!$message->isFirstMessageOnThread())
            return self::SKIP;

        if(!$this->matchPatterns($message))
            return self::SKIP;

        // TODO : Send Shipping Information

        return self::STOP_PROPAGATION;
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
