<?php

// Comprehensive System Setup Script

echo "Setting up Global Logistics CRM System...\n";
echo "=========================================\n\n";

$commands = [
    // Create additional migrations
    "make:migration create_shipment_tracking_table",
    "make:migration create_containers_table",
    "make:migration create_shipment_items_table",
    "make:migration create_invoice_items_table",
    "make:migration create_expenses_table",
    "make:migration create_notifications_table",
    "make:migration create_audit_logs_table",

    // Create Models
    "make:model Branch",
    "make:model Role",
    "make:model Customer",
    "make:model Shipment",
    "make:model ShipmentItem",
    "make:model ShipmentTracking",
    "make:model Container",
    "make:model Invoice",
    "make:model InvoiceItem",
    "make:model Payment",
    "make:model Expense",
    "make:model Notification",
    "make:model AuditLog",

    // Create Controllers
    "make:controller DashboardController",
    "make:controller BranchController --resource",
    "make:controller CustomerController --resource",
    "make:controller ShipmentController --resource",
    "make:controller InvoiceController --resource",
    "make:controller PaymentController --resource",
    "make:controller TrackingController",
    "make:controller ReportController",
    "make:controller UserController --resource",
    "make:controller ProfileController",
    "make:controller NotificationController",

    // Create Seeders
    "make:seeder BranchSeeder",
    "make:seeder RoleSeeder",
    "make:seeder UserSeeder",
    "make:seeder CustomerSeeder",
    "make:seeder ShipmentSeeder",
];

foreach ($commands as $command) {
    echo "Running: php artisan $command\n";
    exec("cd logistics-crm && D:\\laragon\\bin\\php\\php-8.3.16-Win32-vs16-x64\\php.exe artisan $command", $output, $returnCode);

    if ($returnCode === 0) {
        echo "✓ Success\n";
    } else {
        echo "✗ Failed\n";
    }

    // Clear output for next command
    $output = [];
}

echo "\n=========================================\n";
echo "Setup completed! Next steps:\n";
echo "1. Update migration files with proper schemas\n";
echo "2. Run: php artisan migrate\n";
echo "3. Run: php artisan db:seed\n";
echo "4. Access the application at: http://localhost:8000\n";