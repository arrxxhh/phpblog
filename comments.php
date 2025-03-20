<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['post_id'] ?? null;

// Fetch the post to display
$post_stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$post_stmt->execute([$post_id]);
$post = $post_stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comment = trim($_POST['comment']);
    $author = $_SESSION['username'];

    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, author, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $author, $comment]);
    } else {
        $error = "Comment cannot be empty.";
    }
}

// Fetch comments for the post
$comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
$comments_stmt->execute([$post_id]);
$comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($post['body'])) ?></p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <h3>Comments</h3>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <textarea name="comment" class="form-control" placeholder="Add your comment here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>

        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong><?= htmlspecialchars($comment['author']) ?>:</strong> <?= htmlspecialchars($comment['comment']) ?></p>
                        <small class="text-muted"><?= $comment['created_at'] ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
