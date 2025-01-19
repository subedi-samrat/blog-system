<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header('Location: /auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$conn = getConnection();

// Verify post belongs to author
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];
    $slug = slugify($title);
    
    // Handle image upload
    $featured_image = $_POST['current_image'];
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $new_image = uploadImage($_FILES['featured_image'], '../../uploads/');
        if ($new_image) {
            if ($featured_image && file_exists('../../' . $featured_image)) {
                unlink('../../' . $featured_image);
            }
            $featured_image = $new_image;
        }
    }
    
    $stmt = $conn->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, featured_image = ?, category_id = ?, status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssii", $title, $slug, $content, $featured_image, $category_id, $status, $id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    }
}

// Get categories for dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Author Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.0/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link image code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | link image | code',
            height: 400
        });
    </script>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/author-sidebar.php'; ?>
        
        <main class="main-content">
            <h1>Edit Post</h1>
            
            <form method="POST" enctype="multipart/form-data" class="post-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo $category['id'] == $post['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <?php if ($post['featured_image']): ?>
                        <div class="current-image">
                            <img src="../../<?php echo htmlspecialchars($post['featured_image']); ?>" alt="Current image" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($post['featured_image']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Update Post</button>
            </form>
        </main>
    </div>
</body>
</html>