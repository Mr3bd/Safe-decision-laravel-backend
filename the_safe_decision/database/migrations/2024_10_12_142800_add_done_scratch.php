<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
    {
        // Add isActive column to users table
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->boolean('scratches_done')->nullable()->after('price');
            $table->string('scratches_image')->nullable()->after('scratches_done');

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
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->dropColumn('scratches_done');
            $table->dropColumn('scratches_image');
        });

    }
};
