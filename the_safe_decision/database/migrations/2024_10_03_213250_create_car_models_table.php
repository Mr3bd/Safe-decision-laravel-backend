<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained('car_manufacturers')->onDelete('cascade');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('tag_number')->nullable(); // Optional tag number
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_models');
    }
};
