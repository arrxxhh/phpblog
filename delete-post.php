<?php
session_start();
include 'db.php'; // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid post ID.");
}

// Fetch the post to be edited
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle the post update submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, body = ? WHERE id = ?");
    if ($stmt->execute([$title, $body, $id])) {
        // Redirect to admin page after successful update
        header("Location: admin.php");
        exit();
    } else {
        $error = "Error updating post. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Edit Post</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Body:</label>
                <textarea name="body" class="form-control" required><?= htmlspecialchars($post['body']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'footer.php'; // Include the common footer ?>
