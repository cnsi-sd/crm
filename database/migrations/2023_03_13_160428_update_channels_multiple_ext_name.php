<?php

use App\Models\Channel\Channel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->json('ext_names')->nullable()->after('ext_name');
        });

        foreach(Channel::all() as $channel) {
            $channel->ext_names = $channel->ext_name ? [$channel->ext_name] : []; // Convert string values to array
            $channel->save();
        }

        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('ext_name');
        });
    }

    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->string('ext_name')->nullable()->after('ext_names');
        });

        foreach(Channel::all() as $channel) {
            $channel->ext_name = $channel->ext_names[0] ?? null; // Convert array values to string
            $channel->save();
        }

        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('ext_names');
        });
    }
};
