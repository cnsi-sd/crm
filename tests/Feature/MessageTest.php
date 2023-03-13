<?php

namespace Tests\Feature;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_isExternal()
    {
        $assertTrue = [TicketMessageAuthorTypeEnum::CUSTOMER, TicketMessageAuthorTypeEnum::OPERATOR];

        foreach(TicketMessageAuthorTypeEnum::getList() as $item) {
            $message = new Message();
            $message->author_type = $item;
            $isExternal = $message->isExternal();

            if(in_array($item, $assertTrue))
                $this->assertTrue($isExternal);
            else
                $this->assertFalse($isExternal);
        }
    }

    public function test_isFirstMessageOnThread()
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

        $this->assertTrue($firstMessage->isFirstMessageOnThread());

        $secondMessage = new Message();
        $secondMessage->thread_id = $thread->id;
        $secondMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $secondMessage->content = fake()->text;
        $secondMessage->save();

        $this->assertTrue($firstMessage->isFirstMessageOnThread());
        $this->assertFalse($secondMessage->isFirstMessageOnThread());
    }

    public function test_hasBeenAnswered()
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
        $this->assertFalse($firstMessage->hasBeenAnswered());

        $secondMessage = new Message();
        $secondMessage->thread_id = $thread->id;
        $secondMessage->author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
        $secondMessage->content = fake()->text;
        $secondMessage->save();
        $this->assertFalse($firstMessage->hasBeenAnswered());
        $this->assertFalse($secondMessage->hasBeenAnswered());

        $thirdMessage = new Message();
        $thirdMessage->thread_id = $thread->id;
        $thirdMessage->author_type = TicketMessageAuthorTypeEnum::ADMIN;
        $thirdMessage->content = fake()->text;
        $thirdMessage->save();
        $this->assertTrue($firstMessage->hasBeenAnswered());
        $this->assertTrue($secondMessage->hasBeenAnswered());
        $this->assertFalse($thirdMessage->hasBeenAnswered());
    }
}
