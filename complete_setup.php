<?php

// Complete System Setup for Quick International Shipping Company

echo "\n====================================================\n";
echo "    Quick International Shipping Company - COMPLETE SETUP          \n";
echo "====================================================\n\n";

// Database Setup
$dbSetup = <<<'PHP'
<?php
// Create all necessary tables with proper relationships
try {
    $pdo = new PDO('mysql:host=localhost;dbname=logistics_crm', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Drop existing tables if needed (for development only)
    $tables = [
        'payments', 'invoice_items', 'invoices', 'shipment_tracking',
        'shipment_items', 'containers', 'shipments', 'customers',
        'password_reset_tokens', 'sessions', 'cache', 'cache_locks',
        'jobs', 'job_batches', 'failed_jobs', 'users', 'roles', 'branches'
    ];

    echo "Dropping existing tables...\n";
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
    }

    // Create branches table
    echo "Creating branches table...\n";
    $pdo->exec("
        CREATE TABLE branches (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(10) UNIQUE NOT NULL,
            country VARCHAR(50) NOT NULL,
            city VARCHAR(50) NOT NULL,
            address TEXT NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(100) NOT NULL,
            timezone VARCHAR(50) DEFAULT 'UTC',
            currency VARCHAR(3) DEFAULT 'USD',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create roles table
    echo "Creating roles table...\n";
    $pdo->exec("
        CREATE TABLE roles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE NOT NULL,
            display_name VARCHAR(100) NOT NULL,
            description TEXT,
            permissions JSON,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create users table (extend Laravel's default)
    echo "Creating users table...\n";
    $pdo->exec("
        CREATE TABLE users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED,
            role_id BIGINT UNSIGNED,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            language_preference VARCHAR(5) DEFAULT 'en',
            is_active BOOLEAN DEFAULT TRUE,
            remember_token VARCHAR(100),
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
            INDEX idx_branch_role (branch_id, role_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create customers table
    echo "Creating customers table...\n";
    $pdo->exec("
        CREATE TABLE customers (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED NOT NULL,
            customer_code VARCHAR(20) UNIQUE NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            contact_person VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            address TEXT NOT NULL,
            city VARCHAR(50) NOT NULL,
            country VARCHAR(50) NOT NULL,
            postal_code VARCHAR(20),
            payment_terms ENUM('cash', 'credit_7', 'credit_15', 'credit_30', 'credit_45', 'credit_60') DEFAULT 'cash',
            credit_limit DECIMAL(15,2) DEFAULT 0,
            currency VARCHAR(3) DEFAULT 'USD',
            language_preference VARCHAR(5) DEFAULT 'en',
            tax_number VARCHAR(50),
            is_active BOOLEAN DEFAULT TRUE,
            created_by BIGINT UNSIGNED,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (branch_id) REFERENCES branches(id),
            FOREIGN KEY (created_by) REFERENCES users(id),
            INDEX idx_branch_code (branch_id, customer_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create shipments table
    echo "Creating shipments table...\n";
    $pdo->exec("
        CREATE TABLE shipments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED NOT NULL,
            shipment_number VARCHAR(30) UNIQUE NOT NULL,
            customer_id BIGINT UNSIGNED NOT NULL,
            origin_country VARCHAR(50) NOT NULL,
            origin_city VARCHAR(50) NOT NULL,
            origin_address TEXT,
            destination_country VARCHAR(50) NOT NULL,
            destination_city VARCHAR(50) NOT NULL,
            destination_address TEXT,
            service_type ENUM('standard', 'express', 'economy', 'priority') NOT NULL,
            transport_mode ENUM('sea', 'air', 'land', 'rail', 'multimodal') NOT NULL,
            shipment_type ENUM('FCL', 'LCL', 'Bulk', 'Break-bulk', 'RoRo', 'Air-freight') NOT NULL,
            status ENUM('draft', 'booked', 'picked_up', 'in_transit', 'at_port', 'customs_clearance', 'out_for_delivery', 'delivered', 'cancelled', 'returned') DEFAULT 'draft',
            booking_date DATE NOT NULL,
            pickup_date DATE,
            estimated_departure DATETIME,
            estimated_arrival DATETIME,
            actual_departure DATETIME,
            actual_arrival DATETIME,
            total_weight DECIMAL(10,2),
            total_volume DECIMAL(10,2),
            total_pieces INT,
            currency VARCHAR(3) DEFAULT 'USD',
            freight_charge DECIMAL(15,2) DEFAULT 0,
            handling_charge DECIMAL(15,2) DEFAULT 0,
            documentation_charge DECIMAL(15,2) DEFAULT 0,
            customs_charge DECIMAL(15,2) DEFAULT 0,
            other_charges DECIMAL(15,2) DEFAULT 0,
            total_cost DECIMAL(15,2) DEFAULT 0,
            special_instructions TEXT,
            carrier_name VARCHAR(255),
            carrier_booking_ref VARCHAR(100),
            vessel_name VARCHAR(100),
            voyage_number VARCHAR(50),
            flight_number VARCHAR(50),
            truck_number VARCHAR(50),
            bl_number VARCHAR(100),
            awb_number VARCHAR(100),
            created_by BIGINT UNSIGNED NOT NULL,
            assigned_to BIGINT UNSIGNED,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (branch_id) REFERENCES branches(id),
            FOREIGN KEY (customer_id) REFERENCES customers(id),
            FOREIGN KEY (created_by) REFERENCES users(id),
            FOREIGN KEY (assigned_to) REFERENCES users(id),
            INDEX idx_branch_shipment (branch_id, shipment_number, status),
            INDEX idx_customer_date (customer_id, booking_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create shipment_tracking table
    echo "Creating shipment_tracking table...\n";
    $pdo->exec("
        CREATE TABLE shipment_tracking (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            shipment_id BIGINT UNSIGNED NOT NULL,
            status VARCHAR(50) NOT NULL,
            location VARCHAR(255),
            description TEXT,
            timestamp DATETIME NOT NULL,
            created_by BIGINT UNSIGNED,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE,
            FOREIGN KEY (created_by) REFERENCES users(id),
            INDEX idx_shipment_time (shipment_id, timestamp)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create invoices table
    echo "Creating invoices table...\n";
    $pdo->exec("
        CREATE TABLE invoices (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED NOT NULL,
            invoice_number VARCHAR(30) UNIQUE NOT NULL,
            customer_id BIGINT UNSIGNED NOT NULL,
            shipment_id BIGINT UNSIGNED,
            invoice_date DATE NOT NULL,
            due_date DATE NOT NULL,
            subtotal DECIMAL(15,2) NOT NULL,
            tax_rate DECIMAL(5,2) DEFAULT 0,
            tax_amount DECIMAL(15,2) DEFAULT 0,
            discount_amount DECIMAL(15,2) DEFAULT 0,
            total_amount DECIMAL(15,2) NOT NULL,
            paid_amount DECIMAL(15,2) DEFAULT 0,
            balance_amount DECIMAL(15,2) NOT NULL,
            currency VARCHAR(3) DEFAULT 'USD',
            status ENUM('draft', 'sent', 'viewed', 'overdue', 'paid', 'partially_paid', 'cancelled') DEFAULT 'draft',
            notes TEXT,
            terms_conditions TEXT,
            created_by BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (branch_id) REFERENCES branches(id),
            FOREIGN KEY (customer_id) REFERENCES customers(id),
            FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE SET NULL,
            FOREIGN KEY (created_by) REFERENCES users(id),
            INDEX idx_branch_invoice (branch_id, invoice_number, status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create invoice_items table
    echo "Creating invoice_items table...\n";
    $pdo->exec("
        CREATE TABLE invoice_items (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_id BIGINT UNSIGNED NOT NULL,
            description TEXT NOT NULL,
            quantity DECIMAL(10,2) NOT NULL,
            unit_price DECIMAL(15,2) NOT NULL,
            total_price DECIMAL(15,2) NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create payments table
    echo "Creating payments table...\n";
    $pdo->exec("
        CREATE TABLE payments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_id BIGINT UNSIGNED NOT NULL,
            payment_number VARCHAR(30) UNIQUE NOT NULL,
            payment_date DATE NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            currency VARCHAR(3) DEFAULT 'USD',
            payment_method ENUM('cash', 'cheque', 'bank_transfer', 'credit_card', 'online', 'other') NOT NULL,
            reference_number VARCHAR(255),
            bank_name VARCHAR(255),
            cheque_number VARCHAR(100),
            notes TEXT,
            status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
            created_by BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (invoice_id) REFERENCES invoices(id),
            FOREIGN KEY (created_by) REFERENCES users(id),
            INDEX idx_invoice_date (invoice_id, payment_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Create Laravel default tables
    echo "Creating Laravel default tables...\n";

    // Sessions table
    $pdo->exec("
        CREATE TABLE sessions (
            id VARCHAR(255) PRIMARY KEY,
            user_id BIGINT UNSIGNED,
            ip_address VARCHAR(45),
            user_agent TEXT,
            payload LONGTEXT NOT NULL,
            last_activity INT NOT NULL,
            INDEX sessions_user_id_index (user_id),
            INDEX sessions_last_activity_index (last_activity)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Cache table
    $pdo->exec("
        CREATE TABLE cache (
            `key` VARCHAR(255) PRIMARY KEY,
            value MEDIUMTEXT NOT NULL,
            expiration INT NOT NULL,
            INDEX cache_expiration_index (expiration)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Jobs table
    $pdo->exec("
        CREATE TABLE jobs (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            queue VARCHAR(255) NOT NULL,
            payload LONGTEXT NOT NULL,
            attempts TINYINT UNSIGNED NOT NULL,
            reserved_at INT UNSIGNED,
            available_at INT UNSIGNED NOT NULL,
            created_at INT UNSIGNED NOT NULL,
            INDEX jobs_queue_index (queue)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    echo "\n✅ All tables created successfully!\n\n";

    // Insert initial data
    echo "Inserting initial data...\n";

    // Insert branches
    $pdo->exec("
        INSERT INTO branches (name, code, country, city, address, phone, email, timezone, currency) VALUES
        ('Dubai Main Branch', 'DXB001', 'UAE', 'Dubai', 'JAFZA, Dubai, UAE', '+971-4-123-4567', 'dubai@logistics.com', 'Asia/Dubai', 'AED'),
        ('Shanghai Branch', 'SHA001', 'China', 'Shanghai', 'Pudong District, Shanghai', '+86-21-1234-5678', 'shanghai@logistics.com', 'Asia/Shanghai', 'CNY'),
        ('Muscat Branch', 'MCT001', 'Oman', 'Muscat', 'Al Khuwair, Muscat', '+968-2412-3456', 'muscat@logistics.com', 'Asia/Muscat', 'OMR')
    ");

    // Insert roles
    $pdo->exec("
        INSERT INTO roles (name, display_name, description) VALUES
        ('super_admin', 'Super Administrator', 'Full system access across all branches'),
        ('branch_admin', 'Branch Administrator', 'Full access within assigned branch'),
        ('manager', 'Manager', 'Manage operations and staff'),
        ('sales_rep', 'Sales Representative', 'Handle sales and customer relations'),
        ('operations', 'Operations Coordinator', 'Manage shipments and logistics'),
        ('finance', 'Finance Officer', 'Handle invoicing and payments'),
        ('customer_service', 'Customer Service', 'Handle customer inquiries')
    ");

    // Insert admin user
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (branch_id, role_id, name, email, password, phone, is_active) VALUES
        (1, 1, 'System Administrator', 'admin@logistics.com', '$password', '+971-50-123-4567', 1)
    ");

    // Insert sample customers
    $pdo->exec("
        INSERT INTO customers (branch_id, customer_code, company_name, contact_person, email, phone, address, city, country, payment_terms, credit_limit, created_by) VALUES
        (1, 'CUST001', 'ABC Trading LLC', 'John Smith', 'john@abctrading.com', '+971-4-234-5678', 'Business Bay, Dubai', 'Dubai', 'UAE', 'credit_30', 50000, 1),
        (1, 'CUST002', 'XYZ Industries', 'Sarah Johnson', 'sarah@xyzind.com', '+971-4-345-6789', 'DIFC, Dubai', 'Dubai', 'UAE', 'credit_15', 75000, 1),
        (2, 'CUST003', 'Global Tech Co', 'Li Wei', 'liwei@globaltech.cn', '+86-21-234-5678', 'Huangpu District', 'Shanghai', 'China', 'cash', 0, 1)
    ");

    // Insert sample shipments
    $pdo->exec("
        INSERT INTO shipments (
            branch_id, shipment_number, customer_id,
            origin_country, origin_city, destination_country, destination_city,
            service_type, transport_mode, shipment_type, status,
            booking_date, total_weight, total_volume, currency,
            freight_charge, total_cost, created_by
        ) VALUES
        (1, 'SH-2024-001247', 1, 'China', 'Shanghai', 'UAE', 'Dubai', 'standard', 'sea', 'FCL', 'in_transit', CURDATE(), 15000, 45, 'USD', 3500, 4200, 1),
        (1, 'SH-2024-001246', 2, 'Oman', 'Muscat', 'China', 'Shenzhen', 'express', 'air', 'Air-freight', 'delivered', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 500, 2.5, 'USD', 1200, 1500, 1),
        (1, 'SH-2024-001245', 3, 'UAE', 'Dubai', 'China', 'Guangzhou', 'economy', 'sea', 'LCL', 'booked', CURDATE(), 2000, 8, 'USD', 800, 1000, 1)
    ");

    echo "✅ Initial data inserted successfully!\n\n";
    echo "====================================================\n";
    echo "Setup complete! You can now login with:\n";
    echo "Email: admin@logistics.com\n";
    echo "Password: admin123\n";
    echo "====================================================\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
PHP;

// Write and execute the setup script
file_put_contents(__DIR__ . '/db_complete_setup.php', $dbSetup);
exec("D:\\laragon\\bin\\php\\php-8.3.16-Win32-vs16-x64\\php.exe " . __DIR__ . "/db_complete_setup.php", $output);
foreach ($output as $line) {
    echo $line . "\n";
}

echo "\n✅ Complete setup finished!\n";
