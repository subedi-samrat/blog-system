<!-- // includes/footer.php -->
    <!-- <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About MyBlog</h3>
                    <p>Share your thoughts and ideas with the world.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/about.php">About</a></li>
                        <li><a href="/contact.php">Contact</a></li>
                        <li><a href="/privacy.php">Privacy Policy</a></li>
                        <li><a href="/terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect</h3>
                    <div class="social-links">
                        <a href="#" target="_blank">Facebook</a>
                        <a href="#" target="_blank">Twitter</a>
                        <a href="#" target="_blank">Instagram</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> MyBlog. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="/assets/js/main.js"></script>
</body>
</html> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About MyBlog</h3>
                    <p>Share your thoughts and ideas with the world.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo $base_url; ?>index.php">Home</a></li>
                        <li><a href="<?php echo $base_url; ?>categories.php">Categories</a></li>
                        <li><a href="<?php echo $base_url; ?>about.php">About</a></li>
                        <li><a href="<?php echo $base_url; ?>contact.php">Contact</a></li>

                        <!-- <li><a href="<?php echo $base_url; ?>privacy.php">Privacy Policy</a></li> -->
                        <!-- <li><a href="<?php echo $base_url; ?>terms.php">Terms of Service</a></li> -->
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect</h3>
                    <div class="social-links">
                        <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> MyBlog. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo $base_url; ?>assets/js/main.js"></script>
</body>
</html>