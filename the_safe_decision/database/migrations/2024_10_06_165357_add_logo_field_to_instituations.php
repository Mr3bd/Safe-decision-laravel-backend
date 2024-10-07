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
            $table->string('logo_image')->after('institution_type_id'); // replace 'some_column' with the column after which you want to add this
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('logo_image');
        });
    }
};
