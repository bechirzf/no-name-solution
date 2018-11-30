<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telephone_number')->nullable();
            $table->string('peripheral_number')->nullable();
            $table->date('hired_date');
            $table->date('live_date');
            $table->unsignedInteger('manager_id')->nullable();
            $table->unsignedInteger('office_id')->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
