<?php
session_start();
include 'db.php'; // Include the database connection
include 'navbar.php'; // Include the common navbar

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle new post submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);
    $user_id = $_SESSION['user_id'];

    if ($title && $body) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, body, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $body, $user_id]);
        header("Location: index.php");
        exit;
    } else {
        $error = "Both title and body are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Add a New Blog Post</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Body:</label>
                <textarea name="body" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Publish</button>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; // Include the common footer ?>
