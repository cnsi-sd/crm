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
        Schema::create('sav_notes', function (Blueprint $table) {
            $table->id();
            $table->text('manufacturer');
            $table->text('pms_delay');
            $table->text('manufacturer_warranty');
            $table->boolean('gc_plus');
            $table->text('gc_plus_delay')->nullable();
            $table->text('hotline');
            $table->text('brand_email');
            $table->text('brand_information')->nullable();
            $table->text('supplier_information')->nullable();
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
        Schema::dropIfExists('sav_notes');
    }
};
