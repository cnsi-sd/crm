<?php

namespace App\Listeners;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Events\NewMessage;
use App\Helpers\PrestashopGateway;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use DateTime;
use Illuminate\Support\Str;

class SendShippingInformation extends AbstractNewMessageListener
{
    private PrestashopGateway $prestashopGateway;

    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();
        $this->prestashopGateway = new PrestashopGateway();

        if (!$this->canBeProcessed())
            return self::SKIP;

        $prestashopOrder = $this->getPrestashopOrder();

        if ($prestashopOrder['is_fulfillment']) {
            $this->sendAnswer(setting('bot.shipping_information.fulfillment_answer_id'));
            $this->message->thread->ticket->close();
            return self::STOP_PROPAGATION;
        }

        if ($this->isStateInPreparation($prestashopOrder)) {
            if ($this->isOrderWithDelay($prestashopOrder)) {
                $this->sendAnswer(setting('bot.shipping_information.in_preparation_with_delay_answer_id'));
            } else {
                $this->sendAnswer(setting('bot.shipping_information.in_preparation_answer_id'));
            }

            // Close ticket only if the max_ship_date is not reached
            if($this->getOrderDelay($prestashopOrder) !== 0) {
                $this->message->thread->ticket->close();
            }

            return self::STOP_PROPAGATION;
        }

        if ($this->isStateShipped($prestashopOrder)) {
            if ($this->isVirSupplier($prestashopOrder)) {
                $this->sendAnswer(setting('bot.shipping_information.vir_shipped_answer_id'));
            } else {
                $this->sendAnswer(setting('bot.shipping_information.default_shipped_answer_id'));
            }

            $this->message->thread->ticket->close();
            return self::STOP_PROPAGATION;
        }

        return self::SKIP;
    }

    protected function canBeProcessed(): bool
    {
        if (!$this->message->isExternal())
            return false;

        if (!$this->message->isFirstMessageOnThread())
            return false;

        if (!$this->matchPatterns($this->message))
            return false;

        return true;
    }

    private function matchPatterns(Message $message): bool
    {
        $patterns = [
            "(Information sur la livraison|Article non reçu)", // Mirakl
            "(Je n'ai pas reçu mon colis|Ma commande est expédiée, mais je ne l'ai pas reçue)", // Cdiscount
            "(Avez-vous expédié l'article ?)", // Rakuten
            "(Demande de renseignements concernant la livraison d'une commande)", // Amazon
        ];

        $threadSubject = $message->thread->name;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $threadSubject, $matches)) {
                return true;
            }
        }

        return false;
    }

    private function getPrestashopOrder()
    {
        // Get the CRM order across relations
        $crmOrder = $this->message->thread->ticket->order;

        // Call the Prestashop API to get orders data
        $prestashopOrders = $this->prestashopGateway->getOrderInfo(
            $crmOrder->channel_order_number,
            $crmOrder->channel->ext_name,
        );

        if (!$prestashopOrders)
            return null;

        return $prestashopOrders[0];
    }

    private function isStateInPreparation($prestashopOrder): bool
    {
        return Str::contains($prestashopOrder['state']['name'], [
            'paiement accepté',
            'en cours de préparation',
            'en cours de conformité',
            'en cours d\'envoi',
            'expédié de entrepôts cnsi',
        ], true);
    }

    private function isStateShipped($prestashopOrder): bool
    {
        return Str::contains($prestashopOrder['state']['name'], [
            'expédié',
            'expédié sans maj mp',
            'a expédier manuellement',
        ], true);
    }

    protected function isVirSupplier($prestashopOrder): bool
    {
        return Str::contains($prestashopOrder['shipping']['carrier'], 'VIR', true);
    }

    private function getOrderDelay($prestashopOrder): ?int
    {
        $now = new DateTime();
        $max_shipment_date = new DateTime($prestashopOrder['max_shipment_date']);

        if ($max_shipment_date->getTimestamp() > 0) {
            if($now < $max_shipment_date) {
                $diff = $now->diff($max_shipment_date);
                $days_diff = $diff->format('%a');
                return (int)$days_diff;
            }
            else {
                return 0; // Deadline exceeded
            }
        }

        return null;
    }

    private function isOrderWithDelay($prestashopOrder): bool
    {
        $orderDelay = $this->getOrderDelay($prestashopOrder);
        return $orderDelay >= 10;
    }

    private function sendAnswer(int $defaultAnswerId)
    {
        $defaultAnswer = DefaultAnswer::findOrFail($defaultAnswerId);

        // Build message
        $answer = new Message();
        $answer->thread_id = $this->message->thread_id;
        $answer->user_id = null;
        $answer->channel_message_number = '';
        $answer->author_type = TicketMessageAuthorTypeEnum::SYSTEM;
        $answer->content = $defaultAnswer->content;
        $answer->save();

        // Send message
        AbstractSendMessage::dispatchMessage($answer);
    }
}
