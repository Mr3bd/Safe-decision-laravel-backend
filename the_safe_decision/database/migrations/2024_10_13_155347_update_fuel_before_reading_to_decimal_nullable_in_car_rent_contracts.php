<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            // Change the column type to decimal(3,2)
            $table->decimal('fuel_before_reading', 3, 2)->nullable()->change();
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
            // Revert the column type back to int
            $table->integer('fuel_before_reading')->nullable()->change();
        });
    }
};
