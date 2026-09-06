<?php
require_once __DIR__ . '/../bootstrap.php';

require_login();

json_response([
    'user' => [
        'id' => $_SESSION['user_id'],
        'full_name' => $_SESSION['full_name'],
        'role' => $_SESSION['role'],
    ],
]);
