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
        // Add branch_id to users table (default branch)
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'default_branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('default_branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }

        // Add branch_id to shipments table
        if (Schema::hasTable('shipments') && !Schema::hasColumn('shipments', 'branch_id')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }

        // Add branch_id to customers table
        if (Schema::hasTable('customers') && !Schema::hasColumn('customers', 'branch_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }

        // Add branch_id to invoices table
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'branch_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }

        // Add branch_id to payments table
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'branch_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }

        // Add branch_id to expenses table
        if (Schema::hasTable('expenses') && !Schema::hasColumn('expenses', 'branch_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }

        // Add branch_id to revenue table
        if (Schema::hasTable('revenue') && !Schema::hasColumn('revenue', 'branch_id')) {
            Schema::table('revenue', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'shipments', 'customers', 'invoices', 'payments', 'expenses', 'revenue'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if ($tableName === 'users' && Schema::hasColumn('users', 'default_branch_id')) {
                        $table->dropForeign(['default_branch_id']);
                        $table->dropColumn('default_branch_id');
                    } elseif (Schema::hasColumn($tableName, 'branch_id')) {
                        $table->dropForeign(['branch_id']);
                        $table->dropColumn('branch_id');
                    }
                });
            }
        }
    }
};
