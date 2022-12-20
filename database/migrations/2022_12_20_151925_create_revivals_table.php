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
        Schema::create('revivals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('frequency');
            $table->foreignId('default_answer_id')->constrained('default_answers');
            $table->integer('max_revival');
            $table->foreignId('end_default_answer_id')->constrained('default_answers');
            $table->string('end_state');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revivals');
    }
};
