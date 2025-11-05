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
        Schema::create('shipment_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->string('document_type'); // bill_of_lading, commercial_invoice, packing_list, certificate_of_origin, etc.
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, jpg, png, etc.
            $table->integer('file_size')->nullable(); // in bytes
            $table->text('description')->nullable();
            $table->string('status')->default('uploaded'); // uploaded, verified, rejected
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_documents');
    }
};
