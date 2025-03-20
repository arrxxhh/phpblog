<?php
session_start();
require_once 'db.php'; // Make sure to include the database connection
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid post ID.");
}

// Fetch post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch comments
$stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="card-title"><?= htmlspecialchars($post['title']) ?></h1>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['body'])) ?></p>
                <a href="index.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>

        <h2 class="mt-4">Comments</h2>
        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="card comment-box mb-3">
                    <div class="card-body">
                        <p><strong><?= htmlspecialchars($comment['author']) ?>:</strong> <?= htmlspecialchars($comment['comment']) ?> <em>(<?= $comment['created_at'] ?>)</em></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>

        <h3 class="mt-4">Leave a Comment</h3>
        <form action="add-comment.php" method="post">
            <input type="hidden" name="post_id" value="<?= $id ?>">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Comment:</label>
                <textarea name="comment" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
