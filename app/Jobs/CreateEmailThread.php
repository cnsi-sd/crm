<?php

namespace App\Jobs;

use App\Helpers\Prestashop\CrmLinkGateway;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateEmailThread implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle()
    {
        // Don't duplicate email threads
        if ($this->ticket->threads()->where('channel_thread_number', Thread::EMAIL)->exists()) {
            return;
        }

        $order = $this->ticket->order;
        $channel = $order->channel;

        // Get customer email
        $prestashopGateway = new CrmLinkGateway();
        $prestashopOrders = $prestashopGateway->getOrderInfo($order->channel_order_number, $channel->ext_names);

        // Create thread
        if ($prestashopOrders && count($prestashopOrders) > 0) {

            $channel_data = ["email" => $prestashopOrders[0]['email']];
            Thread::getOrCreateThread($this->ticket, Thread::EMAIL, Thread::EMAIL, $channel_data);
        }
    }
}
