<!-- category.php -->
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (!$slug) {
    header('Location: categories.php');
    exit();
}

$conn = getConnection();

// Get category info
$stmt = $conn->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    header('Location: categories.php');
    exit();
}

// Get posts from this category
$stmt = $conn->prepare("
    SELECT p.*, u.username 
    FROM posts p 
    LEFT JOIN users u ON p.user_id = u.id 
    WHERE p.category_id = ? AND p.status = 'published' 
    ORDER BY p.created_at DESC
");
$stmt->bind_param("i", $category['id']);
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - MyBlog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="category-posts">
            <header class="category-header">
                <h1><?php echo htmlspecialchars($category['name']); ?></h1>
                <?php if ($category['description']): ?>
                    <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
                <?php endif; ?>
            </header>

            <?php if (empty($posts)): ?>
                <p class="no-posts">No posts found in this category.</p>
            <?php else: ?>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <div class="post-image">
                                <?php if (!empty($post['featured_image'])): ?>
                                    <img src="/<?php echo htmlspecialchars($post['featured_image']); ?>"
                                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <?php else: ?>
                                    <img src="https://placehold.co/800x400/2563eb/ffffff?text=<?php echo urlencode($post['title']); ?>"
                                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="post-content">
                                <h2>
                                    <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h2>
                                <div class="post-meta">
                                    <span>By <?php echo htmlspecialchars($post['username']); ?></span>
                                    <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                </div>
                                <p><?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?></p>
                                <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">Read
                                    More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>