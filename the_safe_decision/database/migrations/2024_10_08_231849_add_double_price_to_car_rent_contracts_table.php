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
            // Add new column for the double price
            $table->decimal('price', 10, 2)->after('status_id')->nullable();
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
            // Remove the column if rolling back
            $table->dropColumn('price');
        });
    }
};
