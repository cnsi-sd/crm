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
            $table->foreignId('end_tag_id')->after('max_revival')->nullable()->constrained('tags');
            $table->foreignId('end_default_answer_id')->nullable()->change();
            $table->string('end_state')->nullable()->change();
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
            $table->dropForeign('revivals_end_tag_id_foreign');
            $table->dropColumn('end_tag_id');
            $table->foreignId('end_default_answer_id')->nullable(false)->change();
            $table->string('end_state')->nullable(false)->change();
        });
    }
};
