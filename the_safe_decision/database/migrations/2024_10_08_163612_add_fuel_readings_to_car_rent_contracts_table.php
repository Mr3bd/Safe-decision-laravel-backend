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
            $table->decimal('fuel_before_reading', 3, 2)->nullable()->default(null);
            $table->decimal('fuel_after_reading', 3, 2)->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->dropColumn(['fuel_before_reading', 'fuel_after_reading']);
        });
    }
};
