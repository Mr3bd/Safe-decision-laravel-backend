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
        Schema::create('car_rent_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('institution_id')->index('car_rent_contracts_institution_id_foreign');
            $table->dateTime('rent_date');
            $table->dateTime('return_date');
            $table->unsignedBigInteger('car_id')->index('car_rent_contracts_car_id_foreign');
            $table->unsignedBigInteger('status_id')->default(1)->index('car_rent_contracts_status_id_foreign');
            $table->string('front_image');
            $table->string('rear_image');
            $table->string('right_side');
            $table->string('left_side');
            $table->unsignedBigInteger('tenant_id')->index('car_rent_contracts_tenant_id_foreign');
            $table->integer('km_reading_before');
            $table->integer('km_reading_after')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_rent_contracts');
    }
};
