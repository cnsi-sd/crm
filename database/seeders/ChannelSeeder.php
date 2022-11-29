<?php

namespace Database\Seeders;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(){
        foreach (ChannelEnum::getList() as $value)
            Channel::firstOrCreate(['name' => $value],['user_id' => 1]);
    }
}
