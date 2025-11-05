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
        Schema::create('dynamic_options', function (Blueprint $table) {
            $table->id();
            $table->string('option_type', 50); // service_type, transport_mode, shipment_type, weight_unit, volume_unit
            $table->string('value', 255); // The option value
            $table->string('label', 255); // Display label
            $table->integer('sort_order')->default(0); // For ordering options
            $table->boolean('is_active')->default(true); // To enable/disable options
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('option_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_options');
    }
};
