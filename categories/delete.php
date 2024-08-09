<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['username'])) {
    // Not logged in
    header("Location: /login.php");
    exit();
}

// Check user role
if ($_SESSION['role'] !== 'admin') {
    // Only admins can delete posts
    echo "You do not have permission to delete posts.";
    exit();
}

if (isset($_GET['post_id'])) {
    $post_id = (int) $_GET['post_id'];
    
    try {
        $stmt = $db->prepare("DELETE FROM posts WHERE id = :post_id");
        $stmt->execute(['post_id' => $post_id]);
        header("Location: /index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Post ID is required.";
}
?>
