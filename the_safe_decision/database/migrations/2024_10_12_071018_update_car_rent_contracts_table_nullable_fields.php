<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up()
    {
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->date('rent_date')->nullable()->change();
            $table->date('return_date')->nullable()->change();
            $table->unsignedBigInteger('car_id')->nullable()->change();
            $table->unsignedBigInteger('status_id')->nullable()->change();
            $table->integer('km_reading_before')->nullable()->change();
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->integer('fuel_before_reading')->nullable()->change();
            $table->string('front_image')->nullable()->change();
            $table->string('rear_image')->nullable()->change();
            $table->string('right_side')->nullable()->change();
            $table->string('left_side')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->date('rent_date')->nullable(false)->change();
            $table->date('return_date')->nullable(false)->change();
            $table->unsignedBigInteger('car_id')->nullable(false)->change();
            $table->unsignedBigInteger('status_id')->nullable(false)->change();
            $table->integer('km_reading_before')->nullable(false)->change();
            $table->decimal('price', 10, 2)->nullable(false)->change();
            $table->integer('fuel_before_reading')->nullable(false)->change();
            $table->string('front_image')->nullable(false)->change();
            $table->string('rear_image')->nullable(false)->change();
            $table->string('right_side')->nullable(false)->change();
            $table->string('left_side')->nullable(false)->change();
        });
    }
};
