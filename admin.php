<?php
session_start();
require_once 'db.php'; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = getPDO();
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Admin Panel</h1>
        <a href="add-post.php" class="btn btn-primary mb-3">Add New Post</a>

        <h2>All Posts</h2>
        <?php if (count($posts) === 0): ?>
            <div class="alert alert-info">No posts available.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($posts as $post): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong><?= htmlspecialchars($post['title']) ?></strong>
                        <div>
                            <a href="edit-post.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete-post.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this post?')">Delete</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; // Include the common footer ?>
