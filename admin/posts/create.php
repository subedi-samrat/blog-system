<!-- // admin/posts/create.php -->
<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int) $_POST['category_id'];
    $status = $_POST['status'];
    $slug = slugify($title);

    // Handle image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        // Create uploads directory if it doesn't exist
        $upload_dir = '../../uploads/posts/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Get file info
        $file_name = $_FILES['featured_image']['name'];
        $file_tmp = $_FILES['featured_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Generate unique filename
        $new_file_name = uniqid() . '_' . time() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        // Validate file type
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $featured_image = 'uploads/posts/' . $new_file_name;
            }
        }
    }

    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO posts (title, slug, content, featured_image, category_id, user_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiis", $title, $slug, $content, $featured_image, $category_id, $_SESSION['user_id'], $status);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    }
}

// Get categories for dropdown
$conn = getConnection();
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - MyBlog</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <!-- Include TinyMCE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.0/tinymce.min.js"></script>
    <!-- <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link image code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | link image | code'
        });
    </script> -->
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link image code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | link image | code',
            height: 400,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); // This saves content back to the original textarea
                });
            },
            // Prevent auto-hiding of textarea
            hidden_input: false,
            // Ensure content is updated before form submission
            init_instance_callback: function (editor) {
                var form = editor.getElement().form;
                form.onsubmit = function () {
                    editor.save();
                    return true;
                };
            }
        });
    </script>
</head>

<body>
    <div class="admin-container">
        <?php include '../../includes/sidebar.php'; ?>

        <main class="main-content">
            <h1>Create New Post</h1>

            <form method="POST" enctype="multipart/form-data" class="post-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required>
                        <?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                    <div class="image-preview"></div>
                    <small class="form-text">Allowed types: JPG, JPEG, PNG, GIF. Max size: 2MB</small>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Create Post</button>
            </form>
        </main>
    </div>
</body>

</html>