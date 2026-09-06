<?php
require_once __DIR__ . '/bootstrap.php';
require_login();

$total = (int)$pdo->query("SELECT COUNT(*) AS c FROM students")->fetch()['c'];
$active = (int)$pdo->query("SELECT COUNT(*) AS c FROM students WHERE status = 'active'")->fetch()['c'];
$recent = (int)$pdo->query("SELECT COUNT(*) AS c FROM students WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetch()['c'];
$latest = $pdo->query("SELECT id, full_name, email, course, status, created_at FROM students ORDER BY created_at DESC LIMIT 5")->fetchAll();

json_response([
    'total' => $total,
    'active' => $active,
    'recent' => $recent,
    'latest' => $latest,
]);
