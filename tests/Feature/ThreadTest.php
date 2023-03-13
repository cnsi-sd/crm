<?php

namespace Tests\Feature;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    public function test_firstMessage()
    {
        $channel = Channel::firstOrFail();
        $order = fake()->word;

        $order      = Order::getOrder($order, $channel);
        $ticket     = Ticket::getTicket($order, $channel);
        $thread     = Thread::getOrCreateThread($ticket, $order, fake()->sentence, '');

        $firstMessage = new Message();
        $firstMessage->thread_id = $thread->id;
        $firstMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $firstMessage->content = fake()->text;
        $firstMessage->save();
        $this->assertEquals($firstMessage->id, $thread->firstMessage()->id);

        $secondMessage = new Message();
        $secondMessage->thread_id = $thread->id;
        $secondMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $secondMessage->content = fake()->text;
        $secondMessage->save();
        $this->assertEquals($firstMessage->id, $thread->firstMessage()->id);
    }

    public function test_lastMessage()
    {
        $channel = Channel::firstOrFail();
        $order = fake()->word;

        $order      = Order::getOrder($order, $channel);
        $ticket     = Ticket::getTicket($order, $channel);
        $thread     = Thread::getOrCreateThread($ticket, $order, fake()->sentence, '');

        $firstMessage = new Message();
        $firstMessage->thread_id = $thread->id;
        $firstMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $firstMessage->content = fake()->text;
        $firstMessage->save();
        $this->assertEquals($firstMessage->id, $thread->lastMessage()->id);

        $secondMessage = new Message();
        $secondMessage->thread_id = $thread->id;
        $secondMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $secondMessage->content = fake()->text;
        $secondMessage->save();
        $this->assertEquals($secondMessage->id, $thread->lastMessage()->id);
    }

    public function test_getUnreadMessages()
    {
        $channel = Channel::firstOrFail();
        $order = fake()->word;

        $order      = Order::getOrder($order, $channel);
        $ticket     = Ticket::getTicket($order, $channel);
        $thread     = Thread::getOrCreateThread($ticket, $order, fake()->sentence, '');

        $firstMessage = new Message();
        $firstMessage->thread_id = $thread->id;
        $firstMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $firstMessage->content = fake()->text;
        $firstMessage->save();
        $this->assertEquals(1, $thread->getUnreadMessages());

        $secondMessage = new Message();
        $secondMessage->thread_id = $thread->id;
        $secondMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $secondMessage->content = fake()->text;
        $secondMessage->save();
        $this->assertEquals(2, $thread->getUnreadMessages());

        $thirdMessage = new Message();
        $thirdMessage->thread_id = $thread->id;
        $thirdMessage->author_type = TicketMessageAuthorTypeEnum::ADMIN;
        $thirdMessage->content = fake()->text;
        $thirdMessage->save();
        $this->assertEquals(0, $thread->getUnreadMessages());

        $fourthMessage = new Message();
        $fourthMessage->thread_id = $thread->id;
        $fourthMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $fourthMessage->content = fake()->text;
        $fourthMessage->save();
        $this->assertEquals(1, $thread->getUnreadMessages());
    }
}
