<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed.'], 405);
}

$input = read_input();
$full_name = trim($input['full_name'] ?? '');
$email     = trim($input['email'] ?? '');
$password  = $input['password'] ?? '';
$confirm   = $input['confirm_password'] ?? '';

if ($full_name === '' || $email === '' || $password === '') {
    json_response(['error' => 'All fields are required.'], 400);
}
if ($password !== $confirm) {
    json_response(['error' => 'Passwords do not match.'], 400);
}
if (strlen($password) < 6) {
    json_response(['error' => 'Password must be at least 6 characters.'], 400);
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    json_response(['error' => 'An account with this email already exists.'], 409);
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
// Public sign-ups get the 'user' role; only an admin can promote someone in the database.
$stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'user')");
$stmt->execute([$full_name, $email, $hashed]);

json_response(['success' => true, 'message' => 'Account created. You can now log in.']);
