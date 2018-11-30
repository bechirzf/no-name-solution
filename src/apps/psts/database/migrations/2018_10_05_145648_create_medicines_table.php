<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('author', 100)->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_published')->nullable();
            $table->string('site_url', 255);
            $table->string('image_url', 255)->nullable();
            $table->string('created_by', 60);
            $table->string('updated_by', 60)->nullable();
            $table->unsignedInteger('topic_id');
            $table->unsignedInteger('content_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicines');
    }
}
