<?php

namespace App\Listeners;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Events\NewMessage;
use App\Helpers\PrestashopGateway;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;

class SendInvoice extends AbstractNewMessageListener
{
    private PrestashopGateway $prestashopGateway;
    private DefaultAnswer $answerInvoiceFound;
    private DefaultAnswer $answerOrderNotShipped;

    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();
        $this->prestashopGateway = new PrestashopGateway();
        $this->answerInvoiceFound = DefaultAnswer::findOrFail(setting('bot.invoice.found_answer_id'));
        $this->answerOrderNotShipped = DefaultAnswer::findOrFail(setting('bot.invoice.not_shipped_answer_id'));

        if (!$this->canBeProcessed())
            return self::SKIP;

        $invoicesProgress = $this->getInvoicesProgress();

        if (in_array('generated', $invoicesProgress)) {
            $this->sendInvoiceFoundAnswer($invoicesProgress);
            $this->closeTicket();

            return self::STOP_PROPAGATION;
        }
        elseif (in_array('not_shipped_yet', $invoicesProgress)) {
            $this->sendOrderNotShippedAnswer();
            $this->closeTicket();

            return self::STOP_PROPAGATION;
        }
        else {
            return self::SKIP;
        }
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

    private function getInvoicesProgress(): ?array
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

        // Return only invoice progress data
        return array_column($prestashopOrders, 'invoice_progress', 'id_order');
    }

    private function closeTicket()
    {
        $ticket = $this->message->thread->ticket;
        $ticket->state = TicketStateEnum::CLOSED;
        $ticket->save();
    }

    private function sendInvoiceFoundAnswer($invoicesProgress)
    {
        // Search for orders that have a generated invoice
        $generatedInvoices = array_filter($invoicesProgress, fn($progress) => $progress === 'generated');
        $id_orders = array_keys($generatedInvoices);

        // Load PDF invoices
        $pdfs = [];
        foreach ($id_orders as $id_order) {
            $pdfs[] = $this->prestashopGateway->getOrderInvoice($id_order);
        }

        // Build message
        $answer = new Message();
        $answer->thread_id = $this->message->thread_id;
        $answer->user_id = null;
        $answer->channel_message_number = '';
        $answer->author_type = TicketMessageAuthorTypeEnum::SYSTEM;
        $answer->content = $this->answerInvoiceFound->content;
        $answer->save();

        // TODO : add PDF as message attachment

        // Send message
        AbstractSendMessage::dispatchMessage($answer);
    }

    private function sendOrderNotShippedAnswer()
    {
        // Build message
        $answer = new Message();
        $answer->thread_id = $this->message->thread_id;
        $answer->user_id = null;
        $answer->channel_message_number = '';
        $answer->author_type = TicketMessageAuthorTypeEnum::SYSTEM;
        $answer->content = $this->answerOrderNotShipped->content;
        $answer->save();

        // Send message
        AbstractSendMessage::dispatchMessage($answer);
    }
}
