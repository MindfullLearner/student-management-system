<?php
/**
 * Every file under /api/ starts with this: opens the session,
 * connects to the database, and loads the helper functions.
 */
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers.php';
