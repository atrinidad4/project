<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/includes/db_connect.php';

// Determine the user's role and username
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Initialize error message variable
$error = "";

// Handle form submission for creating or updating a post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($userRole === 'admin' || $userRole === 'user')) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($title) || empty($description)) {
        $error = "Title and description are required.";
    } else {
        try {
            if (isset($_POST['id'])) {
                // Update existing post
                $id = $_POST['id'];
                $stmt = $db->prepare("UPDATE posts SET title = :title, description = :description, updated_at = NOW() WHERE id = :id");
                $stmt->bindParam(':id', $id);
            } else {
                // Insert new post
                $stmt = $db->prepare("INSERT INTO posts (title, description, author) VALUES (:title, :description, :author)");
                $stmt->bindParam(':author', $username);
            }

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            // Redirect to avoid resubmission
            header("Location: /posts.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle deletion of a post (only for 'admin' or 'user' roles)
if (isset($_GET['delete']) && ($userRole === 'admin' || $userRole === 'user')) {
    $id = $_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: /posts.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all posts
function fetchAllPosts($db) {
    $stmt = $db->prepare("SELECT posts.*, users.username AS author FROM posts INNER JOIN users ON posts.author = users.username ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$posts = fetchAllPosts($db);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Posts</h1>
        <nav>
            <ul>
                <li><a href="/index.php">Home</a></li>
                <?php if ($userRole === 'admin'): ?>
                    <li><a href="/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <?php if ($username): ?>
                    <li><a href="/authorization/logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Form for creating a new post (only for 'admin' or 'user' roles) -->
        <?php if ($userRole === 'admin' || $userRole === 'user'): ?>
        <section id="create-post">
            <h2>Create New Post</h2>
            <form action="posts.php" method="post">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </section>
        <?php endif; ?>

        <!-- Display all posts -->
        <section id="posts-list">
            <h2>All Posts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <?php if ($userRole === 'admin' || $userRole === 'user'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= htmlspecialchars($post['description']) ?></td>
                            <td><?= htmlspecialchars($post['author']) ?></td>
                            <td><?= htmlspecialchars($post['created_at']) ?></td>
                            <?php if ($userRole === 'admin' || $userRole === 'user'): ?>
                            <td>
                                <!-- Edit button -->
                                <form action="edit_post.php" method="get" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                    <button type="submit">Edit</button>
                                </form>
                                <!-- Delete button -->
                                <form action="posts.php" method="get" style="display:inline;">
                                    <input type="hidden" name="delete" value="<?= $post['id'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
    </footer>
</body>
</html>
