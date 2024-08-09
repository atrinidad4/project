<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include_once __DIR__ . '/includes/db_connect.php';

// Include other initialization files or settings if necessary
include_once __DIR__ . '/includes/auth.php';
?>