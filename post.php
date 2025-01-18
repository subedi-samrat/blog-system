<!-- post.php -->
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (!$slug) {
    header('Location: index.php');
    exit();
}

$conn = getConnection();
$stmt = $conn->prepare("
    SELECT p.*, u.username, u.full_name, c.name as category_name, c.slug as category_slug 
    FROM posts p 
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.slug = ? AND p.status = 'published'
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    header('Location: index.php');
    exit();
}

// Increment view count
$stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
$stmt->bind_param("i", $post['id']);
$stmt->execute();

// Get comments
$stmt = $conn->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    LEFT JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = ? AND c.status = 'approved' 
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $post['id']);
$stmt->execute();
$comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = $post['title'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - MyBlog</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <article class="single-post">
            <div class="post-image">
                <?php if (!empty($post['featured_image'])): ?>
                    <img src="/<?php echo htmlspecialchars($post['featured_image']); ?>"
                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                <?php else: ?>
                    <img src="https://placehold.co/800x400/2563eb/ffffff?text=<?php echo urlencode($post['title']); ?>"
                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                <?php endif; ?>
            </div>
            <header class="post-header">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>

                <div class="post-meta">
                    <span>By <?php echo htmlspecialchars($post['full_name'] ?: $post['username']); ?></span>
                    <span>in <a href="category.php?slug=<?php echo htmlspecialchars($post['category_slug']); ?>">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </a></span>
                    <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                    <span><?php echo number_format($post['views']); ?> views</span>
                </div>
            </header>

            <div class="post-content">
                <?php echo $post['content']; ?>
            </div>

            <section class="comments-section">
                <h2>Comments (<?php echo count($comments); ?>)</h2>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="add-comment.php" class="comment-form">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <div class="form-group">
                            <label for="comment">Add a Comment</label>
                            <textarea id="comment" name="content" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Submit Comment</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="auth/login.php">login</a> to leave a comment.</p>
                <?php endif; ?>

                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-meta">
                                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                <span><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div class="comment-content">
                                <?php echo htmlspecialchars($comment['content']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </article>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>