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
        Schema::table('pending_institutions', function (Blueprint $table) {
            $table->foreign(['institution_type_id'])->references(['id'])->on('institution_types')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_institutions', function (Blueprint $table) {
            $table->dropForeign('pending_institutions_institution_type_id_foreign');
        });
    }
};
