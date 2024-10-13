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
        Schema::table('tenant_car_rent_reviews', function (Blueprint $table) {
            $table->string('national_id')->after('tenant_id'); // replace 'some_column' with the column after which you want to add this
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_car_rent_reviews', function (Blueprint $table) {
            $table->dropColumn('national_id');
        });
    }
};
