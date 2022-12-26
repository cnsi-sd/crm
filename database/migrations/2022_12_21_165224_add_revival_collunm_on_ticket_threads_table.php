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
        Schema::table('ticket_threads', function (Blueprint $table) {
            $table->foreignId('revival_id')->nullable()->after('ticket_id')->constrained();
            $table->date('revival_start_date')->nullable()->after('revival_id');
            $table->integer('revival_message_count')->default(0)->after('revival_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_threads', function (Blueprint $table) {
            $table->dropForeign(['revival_id']);
            $table->dropColumn('revival_id');
            $table->dropColumn('revival_start_date');
            $table->dropColumn('revival_message_count');
        });
    }
};
