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
        Schema::table('user_roles', function (Blueprint $table) {
            // Add 'role_name_ar' after 'role_name'
            $table->string('role_name_ar')->after('role_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_roles', function (Blueprint $table) {
            // Remove the 'role_name_ar' column
            $table->dropColumn('role_name_ar');
        });
    }
};
