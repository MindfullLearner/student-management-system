<?php
require_once __DIR__ . '/../bootstrap.php';
require_login();

$search = trim($_GET['search'] ?? '');
$per_page = 8;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

if ($search !== '') {
    $like = "%$search%";
    $count_stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM students WHERE full_name LIKE ? OR email LIKE ? OR course LIKE ?");
    $count_stmt->execute([$like, $like, $like]);
    $total_rows = (int)$count_stmt->fetch()['c'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE full_name LIKE ? OR email LIKE ? OR course LIKE ? ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
    $stmt->execute([$like, $like, $like]);
} else {
    $total_rows = (int)$pdo->query("SELECT COUNT(*) AS c FROM students")->fetch()['c'];
    $stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
}

$students = $stmt->fetchAll();
$total_pages = max(1, (int)ceil($total_rows / $per_page));

json_response([
    'students' => $students,
    'total' => $total_rows,
    'page' => $page,
    'total_pages' => $total_pages,
]);
