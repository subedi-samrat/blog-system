<!-- // admin/categories/index.php -->
<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

$conn = getConnection();
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - MyBlog</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
    <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Manage Categories</h1>
                <a href="create.php" class="btn-primary">Create New Category</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['slug']); ?></td>
                        <td><?php 
                            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM posts WHERE category_id = ?");
                            $stmt->bind_param("i", $category['id']);
                            $stmt->execute();
                            echo $stmt->get_result()->fetch_assoc()['count'];
                        ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete.php?id=<?php echo $category['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure? This will also delete all posts in this category.')">
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