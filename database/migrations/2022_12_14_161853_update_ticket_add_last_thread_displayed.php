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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('last_thread_displayed')
                ->nullable()
                ->default(null)
                ->after('user_id')
                ->constrained('ticket_threads')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_last_thread_displayed_foreign');
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('last_thread_displayed');
        });
        Schema::table('ticket_threads', function (Blueprint $table) {
            $table->dropIndex('ticket_threads_ticket_id_foreign');
        });
    }
};
