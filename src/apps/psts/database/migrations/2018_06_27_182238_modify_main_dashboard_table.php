<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMainDashboardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_dashboard', function (Blueprint $table) {
            $table->foreign('main_id')
                    ->references('id')->on('mains')
                        ->onDelete('cascade');
            $table->foreign('dashboard_id')
                    ->references('id')->on('dashboards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_dashboard', function (Blueprint $table) {
            $table->dropForeign(['main_id']);
            $table->dropForeign(['dashboard_id']);
        });
    }
}
