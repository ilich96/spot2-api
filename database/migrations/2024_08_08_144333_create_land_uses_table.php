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
        Schema::create('land_uses', function (Blueprint $table) {
            $table->id();
            $table->string('zip_code');
            $table->char('area_colony_type', 1);
            $table->float('land_price');
            $table->float('ground_area');
            $table->float('construction_area');
            $table->float('subsidy');
            $table->index(['postal_code', 'area_colony_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_uses');
    }
};
