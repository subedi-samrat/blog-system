<!-- // search.php -->
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($search) {
    $conn = getConnection();
    $search_term = "%{$search}%";

    $stmt = $conn->prepare("
        SELECT p.*, u.username, c.name as category_name 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE (p.title LIKE ? OR p.content LIKE ?) 
        AND p.status = 'published' 
        ORDER BY p.created_at DESC
    ");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - MyBlog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="search-results">
            <h1>Search Results for "<?php echo htmlspecialchars($search); ?>"</h1>

            <?php if (empty($results)): ?>
                <p class="no-results">No posts found matching your search.</p>
            <?php else: ?>
                <div class="posts-grid">
                    <?php foreach ($results as $post): ?>
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
                                    <span>in <?php echo htmlspecialchars($post['category_name']); ?></span>
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