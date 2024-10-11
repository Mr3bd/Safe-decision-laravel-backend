<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('institution_cars', function (Blueprint $table) {
            $table->year('manu_year')->nullable()->after('tagNumber'); // Add 'manu_year' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institution_cars', function (Blueprint $table) {
            $table->dropColumn('manu_year');
        });
    }
};
