<!-- // admin/categories/create.php -->
<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $slug = slugify($name);
    
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $slug, $description);
    
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category - MyBlog</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
    <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <h1>Create New Category</h1>
            
            <form method="POST" class="post-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>
                
                <button type="submit" class="btn-primary">Create Category</button>
            </form>
        </main>
    </div>
</body>
</html>