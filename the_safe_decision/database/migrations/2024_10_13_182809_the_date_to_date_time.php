<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
    {
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            // Update the columns to be of type datetime
            $table->dateTime('rent_date')->nullable()->change();
            $table->dateTime('return_date')->nullable()->change();
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
            // Revert the columns back to date
            $table->date('rent_date')->nullable()->change();
            $table->date('return_date')->nullable()->change();
        });
    }
};
