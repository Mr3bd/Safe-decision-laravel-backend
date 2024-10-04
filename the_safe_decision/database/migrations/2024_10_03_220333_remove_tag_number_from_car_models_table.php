<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('car_models', function (Blueprint $table) {
            $table->dropColumn('tag_number');
        });
    }

    public function down()
    {
        Schema::table('car_models', function (Blueprint $table) {
            $table->string('tag_number')->nullable();
        });
    }
};
