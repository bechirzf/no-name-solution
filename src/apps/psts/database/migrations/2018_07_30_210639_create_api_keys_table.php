<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('api_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('party_id');
            $table->string('api_key')->unique();
            $table->string('secret_key');
            $table->string('token')->nullable();
            $table->softDeletes('token_expires_at');
            $table->boolean('status')->default(true);
            $table->timestamp('created_at');
            $table->timestamp('expires_at');
            $table->foreign('party_id')
                    ->references('id')->on('users')
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
        Schema::dropIfExists('api_keys');
    }
}
