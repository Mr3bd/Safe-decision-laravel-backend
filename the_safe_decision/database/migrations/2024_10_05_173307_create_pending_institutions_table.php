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
        Schema::create('pending_institutions', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('name');
            $table->string('institution_number');
            $table->timestamps();
            $table->unsignedBigInteger('institution_type_id')->index('pending_institutions_institution_type_id_foreign');

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_institutions');
    }
};
