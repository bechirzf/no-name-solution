<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mains', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')->on('users')
                        ->onDelete('cascade');
            $table->foreign('manager_id')
                    ->references('id')->on('managers')
                        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mains', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['manager_id']);
        });
    }
}