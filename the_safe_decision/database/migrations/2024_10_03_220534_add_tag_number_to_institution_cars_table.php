<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('institution_cars', function (Blueprint $table) {
            $table->string('tagNumber')->nullable(); // Add the tagNumber column
        });
    }

    public function down()
    {
        Schema::table('institution_cars', function (Blueprint $table) {
            $table->dropColumn('tagNumber'); // Drop the tagNumber column if needed
        });
    }
};
