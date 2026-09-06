<?php
/**
 * Small helpers used by every API endpoint.
 */

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/** Reads JSON body OR form-encoded POST body, whichever the client sent. */
function read_input() {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : $_POST;
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        json_response(['error' => 'Not authenticated. Please log in.'], 401);
    }
}

function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        json_response(['error' => "Access denied. This action requires the '$role' role."], 403);
    }
}
