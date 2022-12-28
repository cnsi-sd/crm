<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('channel_revivals', 'channel_revival');
    }

    /*
    public function down()
    {
        Schema::table('many_on_rvival_channel', function (Blueprint $table) {
            //
        });
    }*/
};
