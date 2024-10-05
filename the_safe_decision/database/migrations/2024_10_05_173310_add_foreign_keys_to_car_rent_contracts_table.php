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
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->foreign(['car_id'])->references(['id'])->on('institution_cars')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['institution_id'])->references(['id'])->on('institutions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['status_id'])->references(['id'])->on('car_contract_statuses')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['tenant_id'])->references(['id'])->on('tenants')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_rent_contracts', function (Blueprint $table) {
            $table->dropForeign('car_rent_contracts_car_id_foreign');
            $table->dropForeign('car_rent_contracts_institution_id_foreign');
            $table->dropForeign('car_rent_contracts_status_id_foreign');
            $table->dropForeign('car_rent_contracts_tenant_id_foreign');
        });
    }
};
