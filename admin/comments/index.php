<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

$conn = getConnection();
$comments = $conn->query("
    SELECT c.*, p.title as post_title, u.username 
    FROM comments c 
    LEFT JOIN posts p ON c.post_id = p.id 
    LEFT JOIN users u ON c.user_id = u.id 
    ORDER BY c.created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - MyBlog</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <h1>Manage Comments</h1>
            
            <table>
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>Post</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo substr(htmlspecialchars($comment['content']), 0, 100) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($comment['post_title']); ?></td>
                        <td><?php echo htmlspecialchars($comment['username']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></td>
                        <td>
                            <span class="status-badge status<?php echo $comment['status']; ?>">
                                <?php echo ucfirst($comment['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($comment['status'] === 'pending'): ?>
                            <a href="approve.php?id=<?php echo $comment['id']; ?>" 
                               class="btn-edit" title="Approve">
                                Approve
                            </a>
                            
                            <a href="disapprove.php?id=<?php echo $comment['id']; ?>" 
                               class="btn-mark" title="Disapprove"> 
                               Mark as Spam   
                            </a>
                            <?php endif; ?>
                        
                            <a href="delete.php?id=<?php echo $comment['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this comment?')">
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