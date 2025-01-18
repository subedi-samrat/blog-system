<!-- categories.php -->
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$conn = getConnection();
$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - MyBlog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="categories-section">
            <h1>Browse Categories</h1>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <h2><?php echo htmlspecialchars($category['name']); ?></h2>
                        <p><?php echo htmlspecialchars($category['description']); ?></p>
                        <?php
                            $post_count = $conn->prepare("SELECT COUNT(*) as count FROM posts WHERE category_id = ? AND status = 'published'");
                            $post_count->bind_param("i", $category['id']);
                            $post_count->execute();
                            $count = $post_count->get_result()->fetch_assoc()['count'];
                        ?>
                        <span class="post-count"><?php echo $count; ?> posts</span>
                        <a href="category.php?slug=<?php echo htmlspecialchars($category['slug']); ?>" class="btn-primary">View Posts</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>