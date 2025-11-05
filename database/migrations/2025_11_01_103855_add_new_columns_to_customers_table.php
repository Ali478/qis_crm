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
        Schema::table('customers', function (Blueprint $table) {
            // Add customer type to distinguish between company and individual
            if (!Schema::hasColumn('customers', 'customer_type')) {
                $table->enum('customer_type', ['company', 'individual'])->default('company')->after('id');
            }
            
            // Add fullname for individual customers (nullable, as companies use company_name)
            if (!Schema::hasColumn('customers', 'fullname')) {
                $table->string('fullname')->nullable()->after('customer_type');
            }
            
            // Add representative name (separate from contact_person)
            if (!Schema::hasColumn('customers', 'representative_name')) {
                $table->string('representative_name')->nullable()->after('contact_person');
            }
            
            // Add representative number (phone number for representative)
            if (!Schema::hasColumn('customers', 'representative_number')) {
                $table->string('representative_number')->nullable()->after('representative_name');
            }
            
            // Add wechat contact
            if (!Schema::hasColumn('customers', 'wechat')) {
                $table->string('wechat')->nullable()->after('phone');
            }
            
            // Add whatsapp contact
            if (!Schema::hasColumn('customers', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('wechat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Remove the columns in reverse order
            $columns = ['whatsapp', 'wechat', 'representative_number', 'representative_name', 'fullname', 'customer_type'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('customers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
