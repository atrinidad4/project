<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables
$_SESSION = array();

// If you want to delete the session cookie, you can do so
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy the session
session_destroy();

// Start output buffering to avoid "headers already sent" errors
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <p>You have successfully logged out.</p>
    <p><a href="/index.php">Return to Login Page</a></p>

    <?php
    // Redirect to login page after displaying the message
    header("Refresh: 3; URL=index.php");
    ob_end_flush(); // End output buffering
    ?>
</body>
</html>
