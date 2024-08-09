<?php
include('../includes/db_connect.php');
include('../includes/auth.php');

requireAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];

    $stmt = $conn->prepare("INSERT INTO pages (title, content, category_id) VALUES (:title, :content, :category_id)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    header("Location: ../dashboard.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Create New Page</h2>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Page Title" required>
        <textarea name="content" placeholder="Page Content" required></textarea>
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Create Page</button>
    </form>
</body>
</html>
