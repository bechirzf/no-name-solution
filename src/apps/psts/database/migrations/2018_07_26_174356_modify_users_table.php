<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('manager_id')
                    ->references('id')->on('managers');
            $table->foreign('office_id')
                    ->references('id')->on('offices');
            $table->foreign('position_id')
                    ->references('id')->on('positions');
            $table->foreign('department_id')
                    ->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['office_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['department_id']);
        });
    }
}
