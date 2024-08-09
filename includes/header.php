<?php

// Start session if it hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to the database
include_once __DIR__ . '/db_connect.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']);

// Get the user's role
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>My Website</title>
</head>
<body>
    <header>
    <h1>Directory</h1>
        <nav>
            <ul>
                <li><a href="/index.php">Home</a></li>
                <?php if (!$isLoggedIn): ?>
                    <li><a href="/authorization/login.php">Login</a></li>
                    <li><a href="/authorization/register.php">Register</a></li>
                <?php else: ?>
                    <li><a href="/authorization/logout.php">Logout</a></li>
                    <?php if ($userRole === 'admin'): ?>
                        <li><a href="/dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li><a href="/posts.php">Posts</a></li>
            </ul>
        </nav>
    </header>


