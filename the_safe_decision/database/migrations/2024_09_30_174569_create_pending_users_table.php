<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_users', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('name');
            $table->string('phone_number');
            $table->string('email');
            $table->string('password');
            $table->unsignedBigInteger('institution_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('status_id');
            $table->string('otp');
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
        Schema::dropIfExists('pending_users');
    }
}
