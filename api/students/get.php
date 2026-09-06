<?php
require_once __DIR__ . '/../bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    json_response(['error' => 'Student not found.'], 404);
}

json_response(['student' => $student]);
