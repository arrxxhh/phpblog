<?php
session_start();
include 'db.php';
include 'navbar.php'; // Include the common navbar

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize an empty error message variable
$error = ""; 

// Handle new post submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);
    $user_id = $_SESSION['user_id'];

    // Validate post content
    if (empty($title) || empty($body)) {
        $error = "Title and body cannot be empty.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, body, user_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $body, $user_id])) {
            // Redirect to avoid resubmission
            header("Location: index.php");
            exit();
        } else {
            $error = "Error creating post.";
        }
    }
}

// Fetch posts
$posts = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2 class="mb-3">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h3>Create a Post</h3>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <input type="text" class="form-control" name="title" placeholder="Title" required>
        </div>
        <div class="mb-3">
            <textarea class="form-control" name="body" placeholder="Write your post here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>

    <h3>Recent Posts</h3>
    <?php foreach ($posts as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($post['title']); ?></h5>
                <p class="card-text"><?= htmlspecialchars($post['body']); ?></p>
                <small class="text-muted">By <?= htmlspecialchars($post['username']); ?> at <?= $post['created_at']; ?></small><br>
                <a href="comments.php?post_id=<?= $post['id']; ?>" class="btn btn-sm btn-secondary mt-2">View Comments</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; // Include the common footer ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
