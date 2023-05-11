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
        Schema::table('ticket_thread_messages', function (Blueprint $table) {
            $table->json('reply_to')->nullable()->after('default_answer_id')->default("[]");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_thread_messages', function (Blueprint $table) {
            $table->dropColumn('reply_to');
        });
    }
};
