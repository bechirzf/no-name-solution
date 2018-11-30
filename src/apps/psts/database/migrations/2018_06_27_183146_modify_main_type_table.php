<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMainTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_type', function (Blueprint $table) {
            $table->foreign('main_id')
                    ->references('id')->on('mains')
                        ->onDelete('cascade');
            $table->foreign('type_id')
                    ->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_type', function (Blueprint $table) {
            $table->dropForeign(['main_id']);
            $table->dropForeign(['type_id']);
        });
    }
}
