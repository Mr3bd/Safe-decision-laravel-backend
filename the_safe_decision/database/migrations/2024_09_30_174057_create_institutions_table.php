<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {            // $table->id();
            // $table->string('name');
            // $table->string('institution_number')->unique();
            $table->unsignedBigInteger('institution_type_id');
            DB::table('institutions')->update(['institution_type_id' => 1]); // Set default type (replace 1 with the actual type ID)

            $table->foreign('institution_type_id')
                  ->references('id')
                  ->on('institution_types')
                  ->onDelete('cascade');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Remove the columns and foreign key in case of rollback
            $table->dropForeign(['institution_type_id']);
            $table->dropColumn('institution_type_id');
        });
    }
}
