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
            $table->foreignId('default_answer_id')->nullable()->after('content')->constrained('default_answers');
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
            $table->dropColumn('default_answer_id');
        });
    }
};
