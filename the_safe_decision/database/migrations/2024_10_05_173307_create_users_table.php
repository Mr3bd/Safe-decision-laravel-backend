<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('institution_id')->nullable()->index('users_institution_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('role_id')->index('users_role_id_foreign');
            $table->unsignedBigInteger('status_id')->index('users_status_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
