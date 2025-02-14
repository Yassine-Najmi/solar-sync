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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // panel, inverter, battery, mounting_system, etc.
            $table->string('model');
            $table->string('manufacturer');
            $table->decimal('power_rating', 8, 2); // kW
            $table->decimal('unit_price', 10, 2);
            $table->integer('stock_quantity');
            $table->json('specifications')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
