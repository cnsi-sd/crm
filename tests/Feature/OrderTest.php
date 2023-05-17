<?php

namespace Tests\Feature;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_getOrderChannelUrl()
    {
        // Prepare the channel
        $channel = Channel::getByName(ChannelEnum::CDISCOUNT_FR);
        $channel->order_url = '';
        $channel->save();

        // Prepare an order
        $order = new Order();
        $order->channel_id = $channel->id;
        $order->channel_order_number = "NUMBER";

        $this->assertNull($order->getOrderChannelUrl());

        $channel->order_url = 'https://testmp.com/@';
        $channel->save();
        $order->load('channel'); // refresh channel relation
        $this->assertEquals('https://testmp.com/NUMBER', $order->getOrderChannelUrl());
    }
}
