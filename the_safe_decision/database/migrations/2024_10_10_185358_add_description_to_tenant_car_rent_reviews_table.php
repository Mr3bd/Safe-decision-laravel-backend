<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::table('tenant_car_rent_reviews', function (Blueprint $table) {
            $table->text('description')->nullable()->after('cleanliness');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_car_rent_reviews', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
