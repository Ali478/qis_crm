<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to shipments table
        if (Schema::hasTable('shipments')) {
            Schema::table('shipments', function (Blueprint $table) {
                if (!Schema::hasColumn('shipments', 'tracking_number')) {
                    $table->string('tracking_number')->nullable()->after('id');
                }
                if (!Schema::hasColumn('shipments', 'customer_id')) {
                    $table->unsignedBigInteger('customer_id')->nullable()->after('tracking_number');
                }
                if (!Schema::hasColumn('shipments', 'origin')) {
                    $table->string('origin')->nullable()->after('customer_id');
                }
                if (!Schema::hasColumn('shipments', 'destination')) {
                    $table->string('destination')->nullable()->after('origin');
                }
                if (!Schema::hasColumn('shipments', 'status')) {
                    $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'cancelled'])->default('pending')->after('destination');
                }
                if (!Schema::hasColumn('shipments', 'shipment_type')) {
                    $table->enum('shipment_type', ['sea', 'air', 'land', 'express'])->default('sea')->after('status');
                }
                if (!Schema::hasColumn('shipments', 'weight')) {
                    $table->decimal('weight', 10, 2)->nullable()->after('shipment_type');
                }
                if (!Schema::hasColumn('shipments', 'declared_value')) {
                    $table->decimal('declared_value', 10, 2)->nullable()->after('weight');
                }
                if (!Schema::hasColumn('shipments', 'pickup_date')) {
                    $table->date('pickup_date')->nullable()->after('declared_value');
                }
                if (!Schema::hasColumn('shipments', 'delivery_date')) {
                    $table->date('delivery_date')->nullable()->after('pickup_date');
                }
            });
        }

        // Add missing columns to customers table
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (!Schema::hasColumn('customers', 'company_name')) {
                    $table->string('company_name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('customers', 'contact_person')) {
                    $table->string('contact_person')->nullable()->after('company_name');
                }
                if (!Schema::hasColumn('customers', 'email')) {
                    $table->string('email')->nullable()->after('contact_person');
                }
                if (!Schema::hasColumn('customers', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('customers', 'address')) {
                    $table->text('address')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('customers', 'city')) {
                    $table->string('city')->nullable()->after('address');
                }
                if (!Schema::hasColumn('customers', 'country')) {
                    $table->string('country')->nullable()->after('city');
                }
                if (!Schema::hasColumn('customers', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active')->after('country');
                }
            });
        }

        // Add missing columns to invoices table
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'invoice_number')) {
                    $table->string('invoice_number')->nullable()->after('id');
                }
                if (!Schema::hasColumn('invoices', 'customer_id')) {
                    $table->unsignedBigInteger('customer_id')->nullable()->after('invoice_number');
                }
                if (!Schema::hasColumn('invoices', 'shipment_id')) {
                    $table->unsignedBigInteger('shipment_id')->nullable()->after('customer_id');
                }
                if (!Schema::hasColumn('invoices', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2)->default(0)->after('shipment_id');
                }
                if (!Schema::hasColumn('invoices', 'status')) {
                    $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft')->after('total_amount');
                }
                if (!Schema::hasColumn('invoices', 'due_date')) {
                    $table->date('due_date')->nullable()->after('status');
                }
                if (!Schema::hasColumn('invoices', 'paid_date')) {
                    $table->date('paid_date')->nullable()->after('due_date');
                }
            });
        }

        // Add missing columns to payments table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'invoice_id')) {
                    $table->unsignedBigInteger('invoice_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('payments', 'amount')) {
                    $table->decimal('amount', 10, 2)->default(0)->after('invoice_id');
                }
                if (!Schema::hasColumn('payments', 'payment_method')) {
                    $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer', 'cheque', 'online'])->default('cash')->after('amount');
                }
                if (!Schema::hasColumn('payments', 'payment_date')) {
                    $table->date('payment_date')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('payments', 'reference_number')) {
                    $table->string('reference_number')->nullable()->after('payment_date');
                }
                if (!Schema::hasColumn('payments', 'status')) {
                    $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->after('reference_number');
                }
            });
        }

        // Add missing columns to users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active')->after('email');
                }
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('status');
                }
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role')->default('staff')->after('phone');
                }
                if (!Schema::hasColumn('users', 'branch')) {
                    $table->string('branch')->nullable()->after('role');
                }
            });
        }
    }

    public function down(): void
    {
        // Remove columns from shipments
        if (Schema::hasTable('shipments')) {
            Schema::table('shipments', function (Blueprint $table) {
                $columns = ['tracking_number', 'customer_id', 'origin', 'destination', 'status',
                           'shipment_type', 'weight', 'declared_value', 'pickup_date', 'delivery_date'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('shipments', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Remove columns from customers
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                $columns = ['company_name', 'contact_person', 'email', 'phone', 'address',
                           'city', 'country', 'status'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('customers', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Remove columns from invoices
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $columns = ['invoice_number', 'customer_id', 'shipment_id', 'total_amount',
                           'status', 'due_date', 'paid_date'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('invoices', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Remove columns from payments
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $columns = ['invoice_id', 'amount', 'payment_method', 'payment_date',
                           'reference_number', 'status'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('payments', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Remove columns from users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columns = ['status', 'phone', 'role', 'branch'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};