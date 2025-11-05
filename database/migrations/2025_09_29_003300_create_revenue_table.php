<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->string('transaction_id')->unique();
            $table->string('source'); // shipment, invoice, service_fee, other
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->foreignId('shipment_id')->nullable()->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('transaction_date');
            $table->string('payment_method'); // bank_transfer, cash, cheque, credit_card, online
            $table->string('reference_number')->nullable();
            $table->string('status')->default('completed'); // pending, completed, cancelled
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue');
    }
};