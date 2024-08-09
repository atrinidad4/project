<?php
include('../includes/db_connect.php');
include('../includes/auth.php');

requireAdmin();

$page_id = $_GET['id'];
$page = $conn->prepare("SELECT * FROM pages WHERE id = :id");
$page->bindParam(':id', $page_id);
$page->execute();
$page = $page->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category_id = $_POST['category_id'];

        $stmt = $conn->prepare("UPDATE pages SET title = :title, content = :content, category_id = :category_id WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':id', $page_id);
        $stmt->execute();

        header("Location: ../dashboard.php");
    } elseif (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM pages WHERE id = :id");
        $stmt->bindParam(':id', $page_id);
        $stmt->execute();

        header("Location: ../dashboard.php");
    }
}

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Edit Page</h2>
    <form method="POST" action="">
        <input type="text" name="title" value="<?= $page['title'] ?>" required>
        <textarea name="content" required><?= $page['content'] ?></textarea>
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $page['category_id'] == $category['id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="update">Update Page</button>
        <button type="submit" name="delete">Delete Page</button>
    </form>
</body>
</html>
