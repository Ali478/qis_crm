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

    // First, ensure the tables exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS staff (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED NOT NULL DEFAULT 1,
            employee_id VARCHAR(20) UNIQUE NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            phone VARCHAR(20) NOT NULL,
            department VARCHAR(50) NOT NULL,
            position VARCHAR(100) NOT NULL,
            hire_date DATE NOT NULL,
            basic_salary DECIMAL(10,2) NOT NULL,
            allowances DECIMAL(10,2) DEFAULT 0,
            employment_type VARCHAR(20) NOT NULL,
            status VARCHAR(20) DEFAULT 'active',
            address TEXT,
            city VARCHAR(50),
            country VARCHAR(50),
            birth_date DATE,
            gender VARCHAR(10),
            nationality VARCHAR(50),
            passport_number VARCHAR(50),
            visa_status VARCHAR(50),
            visa_expiry DATE,
            bank_name VARCHAR(100),
            bank_account VARCHAR(50),
            emergency_contact VARCHAR(100),
            emergency_phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS salaries (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            staff_id BIGINT UNSIGNED NOT NULL,
            payment_id VARCHAR(30) UNIQUE NOT NULL,
            month VARCHAR(20) NOT NULL,
            year INT NOT NULL,
            basic_salary DECIMAL(10,2) NOT NULL,
            allowances DECIMAL(10,2) DEFAULT 0,
            overtime DECIMAL(10,2) DEFAULT 0,
            bonuses DECIMAL(10,2) DEFAULT 0,
            deductions DECIMAL(10,2) DEFAULT 0,
            tax DECIMAL(10,2) DEFAULT 0,
            net_salary DECIMAL(10,2) NOT NULL,
            payment_date DATE NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            payment_status VARCHAR(20) DEFAULT 'pending',
            reference_number VARCHAR(50),
            notes TEXT,
            processed_by BIGINT UNSIGNED,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS expenses (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED NOT NULL DEFAULT 1,
            expense_number VARCHAR(30) UNIQUE NOT NULL,
            category VARCHAR(50) NOT NULL,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            amount DECIMAL(10,2) NOT NULL,
            currency VARCHAR(3) DEFAULT 'USD',
            expense_date DATE NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            vendor_name VARCHAR(100),
            invoice_number VARCHAR(50),
            receipt_path VARCHAR(255),
            status VARCHAR(20) DEFAULT 'pending',
            submitted_by BIGINT UNSIGNED NOT NULL DEFAULT 1,
            approved_by BIGINT UNSIGNED,
            approved_date DATE,
            rejection_reason TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS revenue (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            branch_id BIGINT UNSIGNED NOT NULL DEFAULT 1,
            transaction_id VARCHAR(30) UNIQUE NOT NULL,
            source VARCHAR(50) NOT NULL,
            invoice_id BIGINT UNSIGNED,
            shipment_id BIGINT UNSIGNED,
            customer_id BIGINT UNSIGNED,
            description VARCHAR(255) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            currency VARCHAR(3) DEFAULT 'USD',
            transaction_date DATE NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            reference_number VARCHAR(100),
            status VARCHAR(20) DEFAULT 'completed',
            recorded_by BIGINT UNSIGNED NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Insert Staff Data
    $staff = [
        ['EMP0001', 'John', 'Anderson', 'john.anderson@globallogistics.com', '+971-50-123-4567', 'Operations', 'Operations Manager', '2022-03-15', 8500, 1500, 'full_time', 'active', 'Dubai', 'UAE', '1985-06-20', 'Male', 'American'],
        ['EMP0002', 'Sarah', 'Johnson', 'sarah.johnson@globallogistics.com', '+971-50-234-5678', 'Finance', 'Finance Manager', '2021-07-10', 9000, 1800, 'full_time', 'active', 'Abu Dhabi', 'UAE', '1988-03-15', 'Female', 'British'],
        ['EMP0003', 'Mohammed', 'Al-Rashid', 'mohammed.rashid@globallogistics.com', '+971-50-345-6789', 'Logistics', 'Logistics Coordinator', '2023-01-20', 5500, 800, 'full_time', 'active', 'Sharjah', 'UAE', '1990-11-10', 'Male', 'Emirati'],
        ['EMP0004', 'Li', 'Wei', 'li.wei@globallogistics.com', '+971-50-456-7890', 'IT', 'IT Specialist', '2022-09-05', 6000, 1000, 'full_time', 'active', 'Dubai', 'UAE', '1987-04-25', 'Male', 'Chinese'],
        ['EMP0005', 'Emma', 'Wilson', 'emma.wilson@globallogistics.com', '+971-50-567-8901', 'Customer Service', 'Customer Service Manager', '2021-11-15', 5000, 700, 'full_time', 'active', 'Dubai', 'UAE', '1992-08-30', 'Female', 'Australian'],
        ['EMP0006', 'Ahmed', 'Hassan', 'ahmed.hassan@globallogistics.com', '+971-50-678-9012', 'Warehouse', 'Warehouse Supervisor', '2023-03-01', 4500, 600, 'full_time', 'active', 'Ajman', 'UAE', '1989-12-05', 'Male', 'Egyptian'],
        ['EMP0007', 'Maria', 'Garcia', 'maria.garcia@globallogistics.com', '+971-50-789-0123', 'HR', 'HR Manager', '2022-06-20', 7000, 1200, 'full_time', 'active', 'Dubai', 'UAE', '1986-09-18', 'Female', 'Spanish'],
        ['EMP0008', 'James', 'Smith', 'james.smith@globallogistics.com', '+971-50-890-1234', 'Sales', 'Sales Executive', '2023-02-10', 4000, 500, 'full_time', 'active', 'Dubai', 'UAE', '1993-01-22', 'Male', 'Canadian']
    ];

    foreach ($staff as $s) {
        $stmt = $pdo->prepare("
            INSERT INTO staff (employee_id, first_name, last_name, email, phone, department, position, hire_date,
                             basic_salary, allowances, employment_type, status, city, country, birth_date, gender, nationality)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE updated_at = NOW()
        ");
        $stmt->execute($s);
    }
    echo "Staff data inserted successfully\n";

    // Insert Salary Data for current and last month
    $months = [
        ['September', date('Y'), date('Y-m-15')],
        ['October', date('Y'), date('Y-m-d')]
    ];

    $staffIds = $pdo->query("SELECT id, basic_salary, allowances FROM staff")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($months as $month) {
        foreach ($staffIds as $staff) {
            $paymentId = 'PAY-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $basicSalary = $staff['basic_salary'];
            $allowances = $staff['allowances'];
            $overtime = rand(0, 500);
            $bonuses = rand(0, 1000);
            $deductions = rand(0, 200);
            $tax = ($basicSalary + $allowances) * 0.05;
            $netSalary = $basicSalary + $allowances + $overtime + $bonuses - $deductions - $tax;

            $stmt = $pdo->prepare("
                INSERT IGNORE INTO salaries (staff_id, payment_id, month, year, basic_salary, allowances, overtime,
                                          bonuses, deductions, tax, net_salary, payment_date, payment_method, payment_status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'bank_transfer', 'paid')
            ");
            $stmt->execute([
                $staff['id'], $paymentId, $month[0], $month[1], $basicSalary, $allowances,
                $overtime, $bonuses, $deductions, $tax, $netSalary, $month[2]
            ]);
        }
    }
    echo "Salary data inserted successfully\n";

    // Insert Expense Data
    $expenses = [
        ['EXP-2024-0001', 'office', 'Office Supplies Purchase', 'Stationery and printing materials', 450.00, '2024-09-10', 'credit_card', 'Office Mart', 'paid'],
        ['EXP-2024-0002', 'transport', 'Fuel for Delivery Trucks', 'Monthly fuel expenses', 2800.00, '2024-09-15', 'cash', 'ENOC Station', 'paid'],
        ['EXP-2024-0003', 'utilities', 'Electricity Bill', 'DEWA monthly bill', 3200.00, '2024-09-20', 'bank_transfer', 'DEWA', 'paid'],
        ['EXP-2024-0004', 'maintenance', 'Vehicle Maintenance', 'Regular service for fleet', 1500.00, '2024-09-25', 'cheque', 'Auto Service Center', 'paid'],
        ['EXP-2024-0005', 'marketing', 'Digital Marketing Campaign', 'Social media advertising', 2000.00, '2024-10-01', 'credit_card', 'Digital Agency', 'approved'],
        ['EXP-2024-0006', 'rent', 'Warehouse Rent', 'Monthly warehouse rent', 8500.00, '2024-10-05', 'bank_transfer', 'Property Management', 'paid'],
        ['EXP-2024-0007', 'office', 'IT Equipment', 'New computers and printers', 5000.00, '2024-10-10', 'credit_card', 'Tech Solutions', 'pending'],
        ['EXP-2024-0008', 'other', 'Staff Training', 'Professional development workshop', 1200.00, '2024-10-12', 'cash', 'Training Institute', 'pending']
    ];

    foreach ($expenses as $e) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO expenses (expense_number, category, title, description, amount, expense_date,
                                       payment_method, vendor_name, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute($e);
    }
    echo "Expense data inserted successfully\n";

    // Insert Revenue Data
    $revenue = [
        ['TRX-2024-0001', 'shipment', 1, 1, 'Freight charges for shipment SHP-2024-0001', 4500.00, '2024-09-05', 'bank_transfer', 'completed'],
        ['TRX-2024-0002', 'invoice', 1, 2, 'Invoice payment INV-2024-0001', 8750.00, '2024-09-10', 'credit_card', 'completed'],
        ['TRX-2024-0003', 'shipment', 2, 3, 'Freight charges for shipment SHP-2024-0002', 6200.00, '2024-09-15', 'bank_transfer', 'completed'],
        ['TRX-2024-0004', 'service_fee', null, 1, 'Documentation service fee', 500.00, '2024-09-20', 'cash', 'completed'],
        ['TRX-2024-0005', 'shipment', 3, 2, 'Express delivery charges', 3800.00, '2024-09-25', 'credit_card', 'completed'],
        ['TRX-2024-0006', 'invoice', 2, 1, 'Invoice payment INV-2024-0002', 12500.00, '2024-10-01', 'bank_transfer', 'completed'],
        ['TRX-2024-0007', 'other', null, 3, 'Storage fees', 1500.00, '2024-10-05', 'cash', 'completed'],
        ['TRX-2024-0008', 'shipment', 4, 4, 'International shipping charges', 15000.00, '2024-10-10', 'bank_transfer', 'pending']
    ];

    foreach ($revenue as $r) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO revenue (transaction_id, source, shipment_id, customer_id, description, amount,
                                      transaction_date, payment_method, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute($r);
    }
    echo "Revenue data inserted successfully\n";

    echo "\nAll Staff and Finance demo data has been added successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>