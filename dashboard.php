<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/includes/db_connect.php';
include_once __DIR__ . '/includes/header.php';

// Check if the user is logged in (admin or regular user)
if (!isset($_SESSION['role'])) {
    header("Location: /authorization/login.php");
    exit;
}

// Handle sorting parameters
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'created_at'; // Default to 'created_at'
$sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to 'DESC'

// Validate sort column
$allowedSortColumns = ['title', 'created_at', 'updated_at'];
if (!in_array($sortColumn, $allowedSortColumns)) {
    $sortColumn = 'created_at'; // Default sort column if invalid
}

// Fetch Pages
function fetchPages($db, $sortColumn, $sortOrder) {
    $stmt = $db->prepare("SELECT * FROM pages ORDER BY $sortColumn $sortOrder");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pages = fetchPages($db, $sortColumn, $sortOrder);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Adjust path as needed -->
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Success or Error Messages -->
        <?php if (isset($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Pages List -->
        <section>
            <h2>Pages</h2>
            <table>
                <thead>
                    <tr>
                        <th><a href="?sort=title&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>">Title <?= $sortColumn === 'title' ? ($sortOrder === 'ASC' ? '&#9650;' : '&#9660;') : '' ?></a></th>
                        <th><a href="?sort=created_at&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>">Created At <?= $sortColumn === 'created_at' ? ($sortOrder === 'ASC' ? '&#9650;' : '&#9660;') : '' ?></a></th>
                        <th><a href="?sort=updated_at&order=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>">Updated At <?= $sortColumn === 'updated_at' ? ($sortOrder === 'ASC' ? '&#9650;' : '&#9660;') : '' ?></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $page): ?>
                        <tr>
                            <td><?= htmlspecialchars($page['title']) ?></td>
                            <td><?= htmlspecialchars($page['created_at']) ?></td>
                            <td><?= htmlspecialchars($page['updated_at']) ?></td>
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
