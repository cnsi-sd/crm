<?php

namespace Tests\Feature;

use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Tests\TestCase;

class TicketTest extends TestCase
{
    public function test_hasTag()
    {
        // Create a fake ticket
        $channel = Channel::firstOrFail();
        $order = fake()->word;
        $order = Order::getOrder($order, $channel);
        $ticket = Ticket::getTicket($order, $channel);

        // Grab a random tag
        $tag = Tag::firstOrFail();

        $this->assertFalse($ticket->hasTag($tag));

        $ticket->addTag($tag);

        $this->assertTrue($ticket->fresh()->hasTag($tag));
    }

    public function test_updateDeadline()
    {
        // hour < 16h
        $fakeCurrentDate = Carbon::create(2023, 01, 01, 15, 00, 00);
        Carbon::setTestNow($fakeCurrentDate);
        $calculatedDeadline = Ticket::getAutoDeadline()->format('Y-m-d');
        $this->assertEquals('2023-01-01', $calculatedDeadline);

        // hour > 16h
        $fakeCurrentDate = Carbon::create(2023, 01, 01, 16, 00, 00);
        Carbon::setTestNow($fakeCurrentDate);
        $calculatedDeadline = Ticket::getAutoDeadline()->format('Y-m-d');
        $this->assertEquals('2023-01-02', $calculatedDeadline);
    }

    public function test_addTag()
    {
        $channel = Channel::firstOrFail();
        $order = fake()->word;
        $order = Order::getOrder($order, $channel);
        $ticket = Ticket::getTicket($order, $channel);

        $tag = Tag::firstOrFail();
        $ticket->addTag($tag);

        $this->assertTrue($ticket->hasTag($tag));
        $this->assertCount(1, $ticket->tagLists);
        $this->assertCount(1, $ticket->tagLists->first()->tags);
        $this->assertEquals($tag->id, $ticket->tagLists->first()->tags->first()->id);

        $tag = Tag::skip(1)->first();
        $ticket->addTag($tag);
        $this->assertCount(1, $ticket->tagLists);
        $this->assertCount(2, $ticket->tagLists->first()->tags);
    }
}
