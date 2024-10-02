<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('status_id');

            $table->foreign('institution_id')
                  ->references('id')
                  ->on('institutions')
                  ->onDelete('cascade');
            
            $table->foreign('role_id')
                  ->references('id')
                  ->on('user_roles')
                  ->onDelete('cascade');

            $table->foreign('status_id')
                  ->references('id')
                  ->on('user_statuses')
                  ->onDelete('cascade');

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
