<?php
$root = __DIR__; // Set the root directory
$database = $root . '/data/data.sqlite'; // Set the database file path
$dsn = 'sqlite:' . $database; // Data Source Name for SQLite

// Ensure the directory exists for the database
if (!file_exists(dirname($database))) {
    mkdir(dirname($database), 0777, true);
}

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode

    // SQL to create tables if they do not exist
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        );
        
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            body TEXT NOT NULL,
            user_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            author TEXT NOT NULL,
            comment TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        );
    ";

    // Execute the SQL to create tables
    $pdo->exec($sql);

    // Check if the admin user already exists
    $admin_check = $pdo->query("SELECT COUNT(*) FROM users WHERE username='admin'")->fetchColumn();
    if ($admin_check == 0) {
        // Insert admin user with a default password and email
        $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute(['admin', 'admin@example.com', $hashed_password]);
        // Redirect or log the message instead of echoing it
        // Optionally, you can set a session variable for the admin creation message
        $_SESSION['admin_created'] = true; // Set session variable if needed
        header("Location: login.php"); // Redirect to login or admin dashboard
        exit();
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log error to a file for debugging
    die("Database connection error. Please try again later."); // User-friendly message
}
?>
