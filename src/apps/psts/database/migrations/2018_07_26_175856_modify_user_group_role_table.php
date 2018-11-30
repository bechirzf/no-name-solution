<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserGroupRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_group_role', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')->on('users')
                        ->onDelete('cascade');
            // $table->foreign('group_id')
            //         ->references('id')->on('groups')
            //             ->onDelete('cascade');
            $table->foreign('role_id')
                    ->references('id')->on('roles')
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
        Schema::table('user_group_role', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            // $table->dropForeign(['group_id']);
            $table->dropForeign(['role_id']);
        });
    }
}
