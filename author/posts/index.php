<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header('Location: /auth/login.php');
    exit();
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];

$posts = $conn->query("
    SELECT p.*, c.name as category_name 
    FROM posts p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.user_id = $user_id 
    ORDER BY p.created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts - Author Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/author-sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1>My Posts</h1>
                <a href="create.php" class="btn-primary">Create New Post</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $post['status']; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>