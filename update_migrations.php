<?php

// Script to update all migration files with proper schemas

$migrations = [
    // Roles table
    '2025_09_29_002757_create_roles_table.php' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('display_name', 100);
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
PHP,

    // Add branch and role to users
    '2025_09_29_002758_add_branch_and_role_to_users_table.php' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            $table->unsignedBigInteger('role_id')->nullable()->after('branch_id');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('language_preference', 5)->default('en')->after('phone');
            $table->boolean('is_active')->default(true)->after('language_preference');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->index(['branch_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['branch_id', 'role_id', 'phone', 'language_preference', 'is_active']);
        });
    }
};
PHP,

    // Customers table
    '2025_09_29_002759_create_customers_table.php' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('customer_code', 20)->unique();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city', 50);
            $table->string('country', 50);
            $table->string('postal_code', 20)->nullable();
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_45', 'credit_60'])->default('cash');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('language_preference', 5)->default('en');
            $table->string('tax_number', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['branch_id', 'customer_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
PHP,

    // Shipments table
    '2025_09_29_002800_create_shipments_table.php' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('shipment_number', 30)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('origin_country', 50);
            $table->string('origin_city', 50);
            $table->text('origin_address')->nullable();
            $table->string('destination_country', 50);
            $table->string('destination_city', 50);
            $table->text('destination_address')->nullable();
            $table->enum('service_type', ['standard', 'express', 'economy', 'priority']);
            $table->enum('transport_mode', ['sea', 'air', 'land', 'rail', 'multimodal']);
            $table->enum('shipment_type', ['FCL', 'LCL', 'Bulk', 'Break-bulk', 'RoRo', 'Air-freight']);
            $table->enum('status', ['draft', 'booked', 'picked_up', 'in_transit', 'at_port', 'customs_clearance', 'out_for_delivery', 'delivered', 'cancelled', 'returned']);
            $table->date('booking_date');
            $table->date('pickup_date')->nullable();
            $table->datetime('estimated_departure')->nullable();
            $table->datetime('estimated_arrival')->nullable();
            $table->datetime('actual_departure')->nullable();
            $table->datetime('actual_arrival')->nullable();
            $table->decimal('total_weight', 10, 2)->nullable();
            $table->decimal('total_volume', 10, 2)->nullable();
            $table->integer('total_pieces')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('freight_charge', 15, 2)->default(0);
            $table->decimal('handling_charge', 15, 2)->default(0);
            $table->decimal('documentation_charge', 15, 2)->default(0);
            $table->decimal('customs_charge', 15, 2)->default(0);
            $table->decimal('other_charges', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->text('special_instructions')->nullable();
            $table->string('carrier_name')->nullable();
            $table->string('carrier_booking_ref')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('voyage_number')->nullable();
            $table->string('flight_number')->nullable();
            $table->string('truck_number')->nullable();
            $table->string('bl_number')->nullable();
            $table->string('awb_number')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('assigned_to')->references('id')->on('users');
            $table->index(['branch_id', 'shipment_number', 'status']);
            $table->index(['customer_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
PHP,

    // Invoices table
    '2025_09_29_002801_create_invoices_table.php' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('invoice_number', 30)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shipment_id')->nullable();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'sent', 'viewed', 'overdue', 'paid', 'partially_paid', 'cancelled']);
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['branch_id', 'invoice_number', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
PHP,

    // Payments table
    '2025_09_29_002802_create_payments_table.php' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('payment_number', 30)->unique();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'credit_card', 'online', 'other']);
            $table->string('reference_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('cheque_number')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['invoice_id', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
PHP
];

// Update each migration file
foreach ($migrations as $filename => $content) {
    $filepath = __DIR__ . '/logistics-crm/database/migrations/' . $filename;
    if (file_exists($filepath)) {
        file_put_contents($filepath, $content);
        echo "Updated: $filename\n";
    } else {
        echo "File not found: $filename\n";
    }
}

echo "\nAll migrations updated successfully!\n";