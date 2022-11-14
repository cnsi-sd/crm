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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained('channels');
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('user_id')->constrained('users');
            $table->string('state');
            $table->string('priority');
            $table->date('deadline');
            $table->date('delivery_date')->nullable();
            $table->string('direct_customer_email')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
