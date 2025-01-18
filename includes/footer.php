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
                        <li><a href="<?php echo $base_url; ?>about.php">About</a></li>
                        <li><a href="<?php echo $base_url; ?>contact.php">Contact</a></li>
                        <li><a href="<?php echo $base_url; ?>privacy.php">Privacy Policy</a></li>
                        <li><a href="<?php echo $base_url; ?>terms.php">Terms of Service</a></li>
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
    <script src="<?php echo $base_url; ?>assets/js/main.js"></script>
</body>
</html>