<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyModuleGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_group', function (Blueprint $table) {
            $table->foreign('module_id')
                    ->references('id')->on('modules')
                        ->onDelete('cascade');
            $table->foreign('group_id')
                    ->references('id')->on('groups')
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
        Schema::table('module_group', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropForeign(['group_id']);
        });
    }
}
