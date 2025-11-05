<?php

// Add comprehensive demo data to the system

try {
    $pdo = new PDO('mysql:host=localhost;dbname=logistics_crm', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Adding demo data...\n";

    // Add more customers
    $pdo->exec("
        INSERT INTO customers (branch_id, customer_code, company_name, contact_person, email, phone, address, city, country, payment_terms, credit_limit, created_by) VALUES
        (1, 'CUST004', 'Fast Logistics Dubai', 'Ahmed Hassan', 'ahmed@fastlogistics.ae', '+971-4-456-7890', 'Al Nahda, Dubai', 'Dubai', 'UAE', 'credit_45', 100000, 1),
        (1, 'CUST005', 'Emirates Traders', 'Fatima Al-Rashid', 'fatima@emiratestraders.ae', '+971-4-567-8901', 'Deira, Dubai', 'Dubai', 'UAE', 'credit_30', 80000, 1),
        (2, 'CUST006', 'Shanghai Import Export', 'Wang Ming', 'wangming@shanghaiie.cn', '+86-21-345-6789', 'Pudong New Area', 'Shanghai', 'China', 'credit_15', 120000, 1),
        (2, 'CUST007', 'China Global Trade', 'Zhang Wei', 'zhang@chinaglobal.cn', '+86-21-456-7890', 'Jing\'an District', 'Shanghai', 'China', 'cash', 0, 1),
        (3, 'CUST008', 'Oman Shipping Co', 'Sultan Al-Habsi', 'sultan@omanship.om', '+968-2456-7890', 'Ruwi, Muscat', 'Muscat', 'Oman', 'credit_60', 150000, 1),
        (3, 'CUST009', 'Gulf Express Logistics', 'Mohammed Al-Zaabi', 'mohammed@gulfexpress.om', '+968-2567-8901', 'Al Ghubrah, Muscat', 'Muscat', 'Oman', 'credit_30', 90000, 1),
        (1, 'CUST010', 'Tech Solutions MENA', 'Rashid Khan', 'rashid@techsolutions.ae', '+971-4-678-9012', 'Silicon Oasis, Dubai', 'Dubai', 'UAE', 'credit_45', 200000, 1)
    ");

    // Add more shipments with various statuses
    $statuses = ['draft', 'booked', 'picked_up', 'in_transit', 'at_port', 'customs_clearance', 'out_for_delivery', 'delivered'];
    $transport_modes = ['sea', 'air', 'land'];
    $service_types = ['standard', 'express', 'economy', 'priority'];

    $shipments_data = [
        [1, 'SH-2024-001248', 4, 'UAE', 'Dubai', 'China', 'Beijing', 'express', 'air', 'Air-freight', 'in_transit', '2024-09-25', 800, 3.5, 'USD', 2500, 3000, 1],
        [1, 'SH-2024-001249', 5, 'China', 'Guangzhou', 'UAE', 'Abu Dhabi', 'standard', 'sea', 'FCL', 'customs_clearance', '2024-09-20', 18000, 55, 'USD', 4000, 4800, 1],
        [2, 'SH-2024-001250', 6, 'China', 'Shanghai', 'USA', 'Los Angeles', 'economy', 'sea', 'FCL', 'at_port', '2024-09-15', 22000, 65, 'USD', 5500, 6500, 1],
        [2, 'SH-2024-001251', 7, 'China', 'Shenzhen', 'Germany', 'Hamburg', 'standard', 'sea', 'LCL', 'in_transit', '2024-09-22', 5000, 15, 'USD', 1800, 2200, 1],
        [3, 'SH-2024-001252', 8, 'Oman', 'Muscat', 'India', 'Mumbai', 'express', 'air', 'Air-freight', 'delivered', '2024-09-18', 1200, 4, 'USD', 1500, 1800, 1],
        [3, 'SH-2024-001253', 9, 'UAE', 'Dubai', 'Oman', 'Salalah', 'standard', 'land', 'Break-bulk', 'out_for_delivery', '2024-09-24', 3000, 10, 'USD', 800, 1000, 1],
        [1, 'SH-2024-001254', 10, 'Singapore', 'Singapore', 'UAE', 'Dubai', 'priority', 'air', 'Air-freight', 'booked', '2024-09-26', 500, 2, 'USD', 2000, 2400, 1],
        [1, 'SH-2024-001255', 1, 'UAE', 'Dubai', 'UK', 'London', 'express', 'air', 'Air-freight', 'picked_up', '2024-09-27', 600, 2.5, 'USD', 1800, 2200, 1],
        [2, 'SH-2024-001256', 3, 'China', 'Beijing', 'Japan', 'Tokyo', 'priority', 'air', 'Air-freight', 'in_transit', '2024-09-23', 400, 1.5, 'USD', 1200, 1500, 1],
        [1, 'SH-2024-001257', 2, 'India', 'Delhi', 'UAE', 'Dubai', 'standard', 'sea', 'LCL', 'delivered', '2024-09-10', 8000, 25, 'USD', 2200, 2700, 1]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO shipments (
            branch_id, shipment_number, customer_id, origin_country, origin_city,
            destination_country, destination_city, service_type, transport_mode,
            shipment_type, status, booking_date, total_weight, total_volume,
            currency, freight_charge, total_cost, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($shipments_data as $data) {
        $stmt->execute($data);
    }

    // Add tracking history for shipments
    $tracking_data = [
        [1, 'booked', 'Dubai, UAE', 'Shipment booked and confirmed', '2024-09-25 10:00:00', 1],
        [1, 'picked_up', 'Dubai, UAE', 'Package picked up from customer', '2024-09-26 14:30:00', 1],
        [1, 'in_transit', 'Dubai Airport', 'Shipment departed from Dubai', '2024-09-27 08:00:00', 1],

        [8, 'booked', 'Muscat, Oman', 'Booking confirmed', '2024-09-18 09:00:00', 1],
        [8, 'picked_up', 'Muscat, Oman', 'Collected from warehouse', '2024-09-18 15:00:00', 1],
        [8, 'in_transit', 'Muscat Airport', 'Departed to Mumbai', '2024-09-19 10:00:00', 1],
        [8, 'customs_clearance', 'Mumbai, India', 'Customs clearance in progress', '2024-09-19 18:00:00', 1],
        [8, 'delivered', 'Mumbai, India', 'Successfully delivered to customer', '2024-09-20 11:00:00', 1]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO shipment_tracking (
            shipment_id, status, location, description, timestamp, created_by
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($tracking_data as $data) {
        $stmt->execute($data);
    }

    // Add invoices
    $invoice_data = [
        [1, 'INV-2024-0001', 1, 1, '2024-09-25', '2024-10-25', 4200, 5, 210, 0, 4410, 2000, 2410, 'USD', 'partially_paid', 'Sea freight charges for shipment SH-2024-001247', 1],
        [1, 'INV-2024-0002', 2, 2, '2024-09-20', '2024-10-05', 1500, 5, 75, 0, 1575, 1575, 0, 'USD', 'paid', 'Air freight delivery completed', 1],
        [1, 'INV-2024-0003', 4, 4, '2024-09-26', '2024-10-26', 3000, 5, 150, 0, 3150, 0, 3150, 'USD', 'sent', 'Express air freight to Beijing', 1],
        [2, 'INV-2024-0004', 6, 6, '2024-09-15', '2024-10-15', 6500, 0, 0, 500, 6000, 3000, 3000, 'USD', 'partially_paid', 'FCL shipment to USA', 1],
        [3, 'INV-2024-0005', 8, 8, '2024-09-18', '2024-10-18', 1800, 5, 90, 0, 1890, 1890, 0, 'USD', 'paid', 'Express delivery to Mumbai', 1]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO invoices (
            branch_id, invoice_number, customer_id, shipment_id, invoice_date, due_date,
            subtotal, tax_rate, tax_amount, discount_amount, total_amount, paid_amount,
            balance_amount, currency, status, notes, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($invoice_data as $data) {
        $stmt->execute($data);
    }

    // Add invoice items
    $invoice_items = [
        [1, 'Sea Freight Charges', 1, 3500, 3500],
        [1, 'Documentation Fee', 1, 200, 200],
        [1, 'Handling Charges', 1, 500, 500],

        [2, 'Air Freight - Express', 1, 1200, 1200],
        [2, 'Customs Clearance', 1, 300, 300],

        [3, 'Air Freight to Beijing', 1, 2500, 2500],
        [3, 'Airport Handling', 1, 500, 500],

        [4, 'Ocean Freight FCL 40ft', 1, 5500, 5500],
        [4, 'Port Charges', 1, 1000, 1000],

        [5, 'Express Air Delivery', 1, 1500, 1500],
        [5, 'Documentation', 1, 300, 300]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total_price)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($invoice_items as $item) {
        $stmt->execute($item);
    }

    // Add payments
    $payments = [
        [1, 'PAY-2024-0001', '2024-09-26', 2000, 'USD', 'bank_transfer', 'TRF-123456', 'HSBC', null, 'Partial payment received', 'completed', 1],
        [2, 'PAY-2024-0002', '2024-09-21', 1575, 'USD', 'credit_card', 'CC-789012', null, null, 'Full payment', 'completed', 1],
        [4, 'PAY-2024-0003', '2024-09-20', 3000, 'USD', 'cheque', null, 'Emirates NBD', 'CHQ-456789', 'Partial payment', 'completed', 1],
        [5, 'PAY-2024-0004', '2024-09-19', 1890, 'USD', 'online', 'ONL-234567', null, null, 'Online transfer completed', 'completed', 1]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO payments (
            invoice_id, payment_number, payment_date, amount, currency,
            payment_method, reference_number, bank_name, cheque_number,
            notes, status, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($payments as $payment) {
        $stmt->execute($payment);
    }

    // Add more users (staff members)
    $password = password_hash('staff123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (branch_id, role_id, name, email, password, phone, is_active) VALUES
        (1, 3, 'John Manager', 'manager@logistics.com', '$password', '+971-50-234-5678', 1),
        (1, 4, 'Sarah Sales', 'sales@logistics.com', '$password', '+971-50-345-6789', 1),
        (1, 5, 'Mike Operations', 'operations@logistics.com', '$password', '+971-50-456-7890', 1),
        (2, 3, 'Li Manager', 'manager.cn@logistics.com', '$password', '+86-138-1234-5678', 1),
        (3, 3, 'Ahmed Manager', 'manager.om@logistics.com', '$password', '+968-9123-4567', 1)
    ");

    echo "✅ Demo data added successfully!\n";
    echo "Total Customers: " . $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn() . "\n";
    echo "Total Shipments: " . $pdo->query("SELECT COUNT(*) FROM shipments")->fetchColumn() . "\n";
    echo "Total Invoices: " . $pdo->query("SELECT COUNT(*) FROM invoices")->fetchColumn() . "\n";
    echo "Total Users: " . $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}