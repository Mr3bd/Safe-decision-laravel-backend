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
        Schema::create('car_contract_after_vfeatures', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id'); // Referencing the 'id' field in car_rent_contracts table
            $table->unsignedBigInteger('feature_id');  // Referencing the 'id' field in vehicle_features table

            // Define the composite primary key
            $table->primary(['contract_id', 'feature_id']);

            // Foreign key constraints
            $table->foreign('contract_id')
                  ->references('id')
                  ->on('car_rent_contracts')
                  ->onDelete('cascade'); // Optional, define the delete behavior

            $table->foreign('feature_id')
                  ->references('id')
                  ->on('vehicle_features')
                  ->onDelete('cascade'); // Optional, define the delete behavior
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_contract_after_vfeatures');
    }
};
