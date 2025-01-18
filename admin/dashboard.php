<!-- // admin/dashboard.php -->
<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get dashboard statistics
$conn = getConnection();
$stats = [
    'total_posts' => $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'],
    'total_users' => $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
    'total_comments' => $conn->query("SELECT COUNT(*) as count FROM comments")->fetch_assoc()['count'],
    'recent_posts' => $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC)
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MyBlog</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main-content">
            <div style="text-align: center;display: flex; flex-direction: row; justify-content: space-between; align-items: center; ">
                <h1>Dashboard</h1>
                <a href="../index.php" class="btn-primary" style="margin-left: auto;">
                    <i class="fas fa-home"></i>
                    <span>Go to Home</span>
                </a>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Posts</h3>
                    <p class="stat-number"><?php echo $stats['total_posts']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p class="stat-number"><?php echo $stats['total_users']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Comments</h3>
                    <p class="stat-number"><?php echo $stats['total_comments']; ?></p>
                </div>
            </div>

            <section class="recent-posts">
                <h2>Recent Posts</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['recent_posts'] as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['user_id']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($post['status']); ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="posts/edit.php?id=<?php echo $post['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="posts/delete.php?id=<?php echo $post['id']; ?>" class="btn-delete"
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