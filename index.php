<!-- // index.php (Landing Page) -->
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Initialize database on first run
if (!file_exists('config/.initialized')) {
    // Create database if it doesn't exist
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database
    $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->select_db(DB_NAME);

    // Initialize tables and admin
    if (initializeDatabase()) {
        // Create initialization flag file
        file_put_contents('config/.initialized', date('Y-m-d H:i:s'));
    }
}

// Get recent posts only if tables exist
try {
    $recent_posts = getRecentPosts(6);
} catch (Exception $e) {
    $recent_posts = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- <header class="header">
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="logo">MyBlog</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="admin/dashboard.php">Dashboard</a></li>
                        <li><a href="/auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/auth/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        
        <div class="hero">
            <div class="container">
                <h1>Welcome to MyBlog</h1>
                <p>Discover amazing stories and share your thoughts</p>
                <a href="#featured" class="btn-primary">Start Reading</a>
            </div>
        </div>
    </header> -->
    <?php include 'includes/header.php'; ?>

    <div class="hero">
        <div class="container">
            <h1>Welcome to MyBlog</h1>
            <p>Discover amazing stories and share your thoughts</p>
            <a href="#featured" class="btn-primary">Start Reading</a>
        </div>
    </div>
    <main>
        <section id="featured" class="featured-posts">
            <div class="container">
                <h2>Featured Posts</h2>
                <div class="posts-grid">
                    <?php foreach ($recent_posts as $post): ?>
                        <article class="post-card">
                            <?php if ($post['featured_image']): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>"
                                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h3><a
                                        href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                </h3>
                                <div class="post-meta">
                                    <span>By <?php echo htmlspecialchars($post['username']); ?></span>
                                    <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                </div>
                                <p><?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?></p>
                                <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>"
                                    class="read-more">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About MyBlog</h3>
                    <p>A platform for sharing stories, ideas, and knowledge with the world.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link">Facebook</a>
                        <a href="#" class="social-link">Twitter</a>
                        <a href="#" class="social-link">Instagram</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 MyBlog. All rights reserved.</p>
            </div>
        </div>
    </footer> -->

    <?php include 'includes/footer.php'; ?>
</body>

</html>