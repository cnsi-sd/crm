<?php

namespace App\Listeners;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Events\NewMessage;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;

class SendShippingInformation extends AbstractNewMessageListener
{
    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();

        if (!$this->canBeProcessed())
            return self::SKIP;

        $prestashopOrder = $this->getPrestashopOrder();

        if($prestashopOrder->is_fulfillment) {
            $this->sendAnswer(setting('bot.shipping_information.fulfillment_answer_id'));
            $this->message->thread->ticket->close();
            return self::STOP_PROPAGATION;
        }

        // TODO
        if ($this->isStateInPreparation($prestashopOrder)) {
            if ($this->isOrderWithDelay($prestashopOrder)) {
                $this->sendAnswer(setting('bot.shipping_information.in_preparation_with_delay_answer_id'));
                $this->message->thread->ticket->close();
                return self::STOP_PROPAGATION;
            }
            else {
                $this->sendAnswer(setting('bot.shipping_information.in_preparation_answer_id'));
                $this->message->thread->ticket->close();
                return self::STOP_PROPAGATION;
            }
        }

        else if ($this->isStateShipped($prestashopOrder)) {
            $this->getTrackingUrl($prestashopOrder);
            if ($this->isVirSupplier($prestashopOrder))
                $defaultReplyId = Mage::getStoreConfig('crmticket/scripted_answer/response_shipped_vir');
            else {
                $defaultReplyId = Mage::getStoreConfig('crmticket/scripted_answer/response_shipped_default');
            }
        }
        else
            return false;


        return self::STOP_PROPAGATION;
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
