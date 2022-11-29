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
        Schema::create('ticket_thread_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("thread_id")->constrained('ticket_threads');
            $table->foreignId('user_id')->constrained('users');
            $table->text('content');
            $table->boolean('displayed');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ticket_thread_comments');
    }
};
