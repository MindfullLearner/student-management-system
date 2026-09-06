<?php
/**
 * Database connection file. Uses PDO with prepared statements everywhere.
 */

$DB_HOST = "localhost";
$DB_NAME = "student_management";
$DB_USER = "root";      // change to your MySQL username
$DB_PASS = "root";          // change to your MySQL password

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}
