<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatingTypeIdFieldToRatingTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rating_type', function (Blueprint $table) {
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('rating_id');
            $table->foreign('type_id')
                    ->references('id')->on('types');
            $table->foreign('rating_id')
                    ->references('id')->on('ratings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rating_type', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
            $table->dropForeign(['rating_id']);
            $table->dropColumn('rating_id');
        });
    }
}
