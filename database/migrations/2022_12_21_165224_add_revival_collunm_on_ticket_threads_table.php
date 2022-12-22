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
        });
    }
};
