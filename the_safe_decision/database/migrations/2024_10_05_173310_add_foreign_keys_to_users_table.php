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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['institution_id'])->references(['id'])->on('institutions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['role_id'])->references(['id'])->on('user_roles')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['status_id'])->references(['id'])->on('user_statuses')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_institution_id_foreign');
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_status_id_foreign');
        });
    }
};
