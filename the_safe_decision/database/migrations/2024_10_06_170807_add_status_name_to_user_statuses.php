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
        Schema::table('user_statuses', function (Blueprint $table) {
            $table->string('status_name_ar')->after('status_name'); // replace 'some_column' with the column after which you want to add this
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_statuses', function (Blueprint $table) {
            $table->dropColumn('status_name_ar');
        });
    }
};
