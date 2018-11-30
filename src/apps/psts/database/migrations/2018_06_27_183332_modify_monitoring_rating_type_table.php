<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMonitoringRatingTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoring_rating_type', function (Blueprint $table) {
            $table->foreign('monitoring_id')
                    ->references('id')->on('monitorings')
                        ->onDelete('cascade');
            $table->foreign('rating_type_id')
                    ->references('id')->on('rating_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitoring_rating_type', function (Blueprint $table) {
            $table->dropForeign(['monitoring_id']);
            $table->dropForeign(['rating_type_id']);
        });
    }
}
