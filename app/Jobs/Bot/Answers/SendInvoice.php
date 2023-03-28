<?php

namespace App\Jobs\Bot\Answers;

use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Helpers\Prestashop\CrmLinkGateway;
use App\Helpers\TmpFile;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use Cnsi\Attachments\Model\Document;

class SendInvoice extends AbstractAnswer
{
    private CrmLinkGateway $prestashopGateway;
    private DefaultAnswer $answerInvoiceFound;
    private DefaultAnswer $answerOrderNotShipped;

    public function handle(): bool
    {
        $this->prestashopGateway = new CrmLinkGateway();
        $this->answerInvoiceFound = DefaultAnswer::findOrFail(setting('bot.invoice.found_answer_id'));
        $this->answerOrderNotShipped = DefaultAnswer::findOrFail(setting('bot.invoice.not_shipped_answer_id'));

        if (!$this->canBeProcessed())
            return self::SKIP;

        $invoicesProgress = $this->getInvoicesProgress();

        if (in_array('generated', $invoicesProgress)) {
            $this->sendInvoiceFoundAnswer($invoicesProgress);
            $this->message->thread->ticket->close();

            return self::STOP_PROPAGATION;
        }
        elseif (in_array('not_shipped_yet', $invoicesProgress)) {
            $this->sendOrderNotShippedAnswer();
            $this->message->thread->ticket->close();

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

        if($this->message->hasBeenAnswered())
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
        $prestashopOrders = $crmOrder->getPrestashopOrders();
        if (!$prestashopOrders)
            return null;

        // Return only invoice progress data
        return array_column($prestashopOrders, 'invoice_progress', 'id_order');
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

        foreach ($pdfs as $pdf) {
            $tmpFile = new TmpFile((string) $pdf);
            Document::doUpload($tmpFile, $answer, MessageDocumentTypeEnum::CUSTOMER_INVOICE, 'pdf');
        }

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
