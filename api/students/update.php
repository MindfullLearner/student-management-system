<?php
require_once __DIR__ . '/../bootstrap.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed.'], 405);
}

$input = read_input();
$id = (int)($input['id'] ?? $_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT id FROM students WHERE id = ?");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    json_response(['error' => 'Student not found.'], 404);
}

$full_name = trim($input['full_name'] ?? '');
$email     = trim($input['email'] ?? '');
$phone     = trim($input['phone'] ?? '');
$course    = trim($input['course'] ?? '');
$enrollment_date = $input['enrollment_date'] ?? null;
$status    = in_array($input['status'] ?? '', ['active','inactive']) ? $input['status'] : 'active';

if ($full_name === '' || $email === '') {
    json_response(['error' => 'Full name and email are required.'], 400);
}

$stmt = $pdo->prepare("UPDATE students SET full_name=?, email=?, phone=?, course=?, enrollment_date=?, status=? WHERE id=?");
$stmt->execute([$full_name, $email, $phone, $course, $enrollment_date ?: null, $status, $id]);

json_response(['success' => true]);
