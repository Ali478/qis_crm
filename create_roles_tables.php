<?php

// Database connection
$host = 'localhost';
$dbname = 'logistics_crm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database successfully\n";

    // Create roles table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE NOT NULL,
            display_name VARCHAR(100),
            description TEXT,
            permissions JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Create users table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role_id BIGINT UNSIGNED,
            is_active BOOLEAN DEFAULT TRUE,
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
        )
    ");

    // Insert default roles
    $roles = [
        ['Super Admin', 'Super Administrator', 'Full system access and control', '["*"]'],
        ['Manager', 'Department Manager', 'Management level access with most permissions', '["view_dashboard","view_shipments","create_shipments","edit_shipments","view_customers","create_customers","edit_customers","view_invoices","create_invoices","edit_invoices","view_staff","view_finance","view_reports"]'],
        ['Accountant', 'Accountant', 'Financial operations and reporting access', '["view_dashboard","view_invoices","create_invoices","edit_invoices","view_finance","manage_revenue","manage_expenses","view_reports"]'],
        ['Operations', 'Operations Staff', 'Shipment and customer management access', '["view_dashboard","view_shipments","create_shipments","edit_shipments","view_customers","create_customers","edit_customers","view_invoices"]'],
        ['HR Manager', 'Human Resources Manager', 'Staff and role management access', '["view_dashboard","view_staff","create_staff","edit_staff","manage_salaries","manage_roles"]'],
        ['Employee', 'Regular Employee', 'Basic access to view operations', '["view_dashboard","view_shipments","view_customers","view_invoices"]']
    ];

    foreach ($roles as $role) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO roles (name, display_name, description, permissions)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute($role);
    }

    // Insert demo users
    $users = [
        ['Admin User', 'admin@globallogistics.com', password_hash('admin123', PASSWORD_DEFAULT), 1],
        ['John Manager', 'john.manager@globallogistics.com', password_hash('manager123', PASSWORD_DEFAULT), 2],
        ['Sarah Accountant', 'sarah.accountant@globallogistics.com', password_hash('account123', PASSWORD_DEFAULT), 3],
        ['Mike Operations', 'mike.ops@globallogistics.com', password_hash('ops123', PASSWORD_DEFAULT), 4],
        ['Lisa HR', 'lisa.hr@globallogistics.com', password_hash('hr123', PASSWORD_DEFAULT), 5],
        ['Tom Employee', 'tom.employee@globallogistics.com', password_hash('emp123', PASSWORD_DEFAULT), 6]
    ];

    foreach ($users as $user) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO users (name, email, password, role_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute($user);
    }

    echo "Roles and users tables created successfully\n";
    echo "Default roles and demo users added\n";
    echo "\nDemo login credentials:\n";
    echo "Admin: admin@globallogistics.com / admin123\n";
    echo "Manager: john.manager@globallogistics.com / manager123\n";
    echo "Accountant: sarah.accountant@globallogistics.com / account123\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>