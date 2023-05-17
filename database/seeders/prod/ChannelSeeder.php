<?php

namespace Database\Seeders\prod;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(){
        // TODO : user 1 may not exists on the first seeder execution in production
        foreach (ChannelEnum::getList() as $value)
            Channel::firstOrCreate(
                ['name' => $value],
                ['user_id' => 1, 'ext_names' => []],
            );
    }
}
