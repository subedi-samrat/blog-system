<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Get the base URL
$base_url = '/';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - MyBlog' : 'MyBlog'; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <a href="<?php echo $base_url; ?>" class="logo">MyBlog</a>
                <!-- Mobile menu toggle -->
                <button class="menu-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar">
                    <form action="<?php echo $base_url; ?>search.php" method="GET">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <ul class="nav-links">
                    <li><a href="<?php echo $base_url; ?>" <?php echo $current_page === 'index' ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="<?php echo $base_url; ?>categories.php" <?php echo $current_page === 'categories' ? 'class="active"' : ''; ?>>Categories</a></li>
                    <li><a href="<?php echo $base_url; ?>about.php" <?php echo $current_page === 'about' ? 'class="active"' : ''; ?>>About</a></li>
                    <li><a href="<?php echo $base_url; ?>contact.php" <?php echo $current_page === 'contact' ? 'class="active"' : ''; ?>>Contact</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-dropdown">
                            <i class="fa-solid fa-user dropdown-toggle"></i>
                            <div class="dropdown-menu">
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="<?php echo $base_url; ?>admin/dashboard.php" <?php echo strpos($current_page, 'dashboard') !== false ? 'class="active"' : ''; ?>> <i
                                            class="fas fa-tachometer-alt"></i>Dashboard</a>
                                <?php elseif ($_SESSION['role'] === 'author'): ?>
                                    <a href="<?php echo $base_url; ?>author/dashboard.php">
                                        <i class="fas fa-newspaper"></i>Author Dashboard
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo $base_url; ?>profile.php" <?php echo $current_page === 'profile' ? 'class="active"' : ''; ?>><i class="fas fa-user"></i>Profile</a>
                                <a href="<?php echo $base_url; ?>auth/logout.php"><i
                                        class="fas fa-sign-out-alt"></i>Logout</a>
                            </div>
                        </div>
                    <?php else: ?>

                        <li class="login-btn">
                            <a href="<?php echo $base_url; ?>auth/login.php">Login</a>
                        </li>

                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>