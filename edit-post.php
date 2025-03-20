<?php
session_start();
?>
<?php
require_once 'lib/common.php';
$pdo = getPDO();

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid post ID.");

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $body = $_POST['body'];

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, body = ? WHERE id = ?");
    $stmt->execute([$title, $body, $id]);

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Edit Post</h2>
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
</body>
</html>
