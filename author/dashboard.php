<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header('Location: ../auth/login.php');
    exit();
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];

// Get author's statistics
$stats = [
    'total_posts' => $conn->query("SELECT COUNT(*) as count FROM posts WHERE user_id = $user_id")->fetch_assoc()['count'],
    'published_posts' => $conn->query("SELECT COUNT(*) as count FROM posts WHERE user_id = $user_id AND status = 'published'")->fetch_assoc()['count'],
    'draft_posts' => $conn->query("SELECT COUNT(*) as count FROM posts WHERE user_id = $user_id AND status = 'draft'")->fetch_assoc()['count'],
    'recent_posts' => $conn->query("SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC)
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Dashboard - MyBlog</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/author-sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1>Author Dashboard</h1>
                <a href="../index.php" class="btn-primary">View Blog</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Posts</h3>
                    <p class="stat-number"><?php echo $stats['total_posts']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Published Posts</h3>
                    <p class="stat-number"><?php echo $stats['published_posts']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Draft Posts</h3>
                    <p class="stat-number"><?php echo $stats['draft_posts']; ?></p>
                </div>
            </div>

            <section class="recent-posts">
                <h2>Recent Posts</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['recent_posts'] as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $post['status']; ?>">
                                    <?php echo ucfirst($post['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="posts/edit.php?id=<?php echo $post['id']; ?>" class="btn-edit">Edit</a>
                                <a href="posts/delete.php?id=<?php echo $post['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>