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
        Schema::table('revivals', function (Blueprint $table) {
            $table->foreignId('end_tag_id')->after('max_revival')->constrained('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('revivals', function (Blueprint $table) {
            $table->dropForeign('end_tag_id_foreign');
            $table->dropColumn('end_tag_id');
        });
    }
};
