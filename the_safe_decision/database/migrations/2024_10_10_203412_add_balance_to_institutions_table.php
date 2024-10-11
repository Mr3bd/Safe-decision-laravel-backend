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
            $table->double('balance', 15, 2)->default(10)->after('institution_number'); 
            // Replace 'some_column' with the column name after which you want to place 'balance'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
