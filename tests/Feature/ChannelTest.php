<?php

namespace Tests\Feature;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    public function test_getByExtName()
    {
        // Prepare the channel
        $channel = Channel::getByName(ChannelEnum::CDISCOUNT_FR);
        $channel->ext_names = ['cdiscount', 'cdiscount_fulfillment'];
        $channel->save();

        $found = Channel::getByExtName('cdiscount');
        $this->assertEquals($channel->id, $found->id);

        $found = Channel::getByExtName('cdiscount_fulfillment');
        $this->assertEquals($channel->id, $found->id);

        $found = Channel::getByExtName('amazon');
        $this->assertNull($found);
    }
}
