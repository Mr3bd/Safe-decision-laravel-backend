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
       // Add isActive column to institutions table
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('address_en')->after('institution_type_id');
            $table->string('address_ar')->after('institution_type_id');
            $table->string('emergency_number')->after('institution_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('address_en');
            $table->dropColumn('address_ar');
            $table->dropColumn('emergency_number');
        });
    }
};
