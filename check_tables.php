<?php

$host = 'localhost';
$dbname = 'logistics_crm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    echo "Roles table structure:\n";
    $result = $pdo->query('DESCRIBE roles');
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' | ' . $row['Type'] . "\n";
    }

    echo "\nUsers table structure:\n";
    $result = $pdo->query('DESCRIBE users');
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' | ' . $row['Type'] . "\n";
    }

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>