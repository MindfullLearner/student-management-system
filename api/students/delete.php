<?php
require_once __DIR__ . '/../bootstrap.php';
require_role('admin');

$input = read_input();
$id = (int)($input['id'] ?? $_GET['id'] ?? 0);

if ($id <= 0) {
    json_response(['error' => 'A valid student id is required.'], 400);
}

$stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
$stmt->execute([$id]);

json_response(['success' => true]);
