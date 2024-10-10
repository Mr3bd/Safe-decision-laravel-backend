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
        Schema::create('tenant_car_rent_reviews', function (Blueprint $table) {
            // Define the primary keys
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('tenant_id');

            // Define review fields with constraints
            $table->integer('appointments')->default(0)->between(0, 100);
            $table->integer('accidents')->default(0)->between(0, 100);
            $table->integer('violations')->default(0)->between(0, 100);
            $table->integer('financial')->default(0)->between(0, 100);
            $table->integer('cleanliness')->default(0)->between(0, 100);

            // Define foreign key constraints
            $table->foreign('contract_id')->references('id')->on('car_rent_contracts')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // Define the composite primary key
            $table->primary(['contract_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_car_rent_reviews');
    }
};
