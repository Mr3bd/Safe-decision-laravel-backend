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
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('address_ar')->nullable()->change();
            $table->string('address_en')->nullable()->change();
            $table->string('logo_image')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('address_en')->nullable(false)->change();
            $table->string('address_ar')->nullable(false)->change();
            $table->string('logo_image')->nullable(false)->change();
        });
    }
};
