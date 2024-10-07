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
        // Add isActive column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('isActive')->default(1)->after('status_id');
        });

        // Add isActive column to institutions table
        Schema::table('institutions', function (Blueprint $table) {
            $table->boolean('isActive')->default(1)->after('institution_type_id'); // replace 'some_column' with the column after which you want to add this
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove isActive column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('isActive');
        });

        // Remove isActive column from institutions table
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('isActive');
        });
    }
};
