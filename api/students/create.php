<?php
require_once __DIR__ . '/bootstrap.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed.'], 405);
}

$input = read_input();
$full_name = trim($input['full_name'] ?? '');
$email     = trim($input['email'] ?? '');
$phone     = trim($input['phone'] ?? '');
$course    = trim($input['course'] ?? '');
$enrollment_date = $input['enrollment_date'] ?? null;
$status    = in_array($input['status'] ?? '', ['active','inactive']) ? $input['status'] : 'active';

if ($full_name === '' || $email === '') {
    json_response(['error' => 'Full name and email are required.'], 400);
}

$stmt = $pdo->prepare("INSERT INTO students (full_name, email, phone, course, enrollment_date, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$full_name, $email, $phone, $course, $enrollment_date ?: null, $status]);

json_response(['success' => true, 'id' => $pdo->lastInsertId()], 201);
