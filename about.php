<!-- about.php -->
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MyBlog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="about-section">
            <h1>About MyBlog</h1>
            <div class="about-content">
                <div class="about-text">
                    <h2>Our Story</h2>
                    <p>Welcome to MyBlog, a platform dedicated to sharing knowledge, ideas, and stories that matter. 
                    Founded in 2025, we've been committed to providing quality content across various topics.</p>
                    
                    <h2>Our Mission</h2>
                    <p>We aim to create a space where writers and readers can connect, share insights, and engage 
                    in meaningful discussions. Our platform supports both established and emerging voices in the 
                    blogging community.</p>
                    
                    <h2>Join Our Community</h2>
                    <p>Whether you're a reader or a writer, we welcome you to be part of our growing community. 
                    Share your thoughts, engage with others, and discover new perspectives.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>