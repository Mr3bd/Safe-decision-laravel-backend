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
        Schema::table('institution_cars', function (Blueprint $table) {
            $table->foreign(['institution_id'])->references(['id'])->on('institutions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['model_id'])->references(['id'])->on('car_models')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_cars', function (Blueprint $table) {
            $table->dropForeign('institution_cars_institution_id_foreign');
            $table->dropForeign('institution_cars_model_id_foreign');
        });
    }
};
