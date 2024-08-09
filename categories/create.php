<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['username'])) {
    // Not logged in
    header("Location: /login.php");
    exit();
}

// Check user role
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user') {
    // Unauthorized role
    echo "You do not have permission to create posts.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Basic validation
    if (empty($title) || empty($content)) {
        $error = "Title and content are required.";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO posts (title, content, author) VALUES (:title, :content, :author)");
            $stmt->execute(['title' => $title, 'content' => $content, 'author' => $_SESSION['username']]);
            header("Location: /index.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!-- HTML form to create a post -->
<form action="create_post.php" method="post">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <label for="content">Content:</label>
    <textarea id="content" name="content" required></textarea>
    <button type="submit">Create Post</button>
</form>
