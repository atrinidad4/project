<?php
session_start(); // Start the session

// Connect to the database
include(__DIR__ . '/includes/header.php');
include_once __DIR__ . '/includes/db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Minecraft Block Database</h1>
    </header>

    <main>
        <p>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>!</p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
    </footer>
</body>
</html>

