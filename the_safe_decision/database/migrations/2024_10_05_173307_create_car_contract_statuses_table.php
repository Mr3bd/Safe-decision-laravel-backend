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
        Schema::create('car_contract_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('color', 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_contract_statuses');
    }
};
