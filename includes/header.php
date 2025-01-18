<!-- 
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - MyBlog' : 'MyBlog'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <a href="/" class="logo">MyBlog</a>
                <div class="search-bar">
                    <form action="/search.php" method="GET">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/categories.php">Categories</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="/admin/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="/profile.php">Profile</a></li>
                        <li><a href="/auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/auth/login.php">Login</a></li>
                        <li><a href="/auth/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header> -->

<!-- 
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - MyBlog' : 'MyBlog'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="logo">MyBlog</a>
                <div class="search-bar">
                    <form action="/search.php" method="GET">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php" <?php echo $current_page === 'index' ? 'class="active"' : ''; ?>>Home</a>
                    </li>
                    <li><a href="categories.php" <?php echo $current_page === 'categories' ? 'class="active"' : ''; ?>>Categories</a></li>
                    <li><a href="about.php" <?php echo $current_page === 'about' ? 'class="active"' : ''; ?>>About</a>
                    </li>
                    <li><a href="contact.php" <?php echo $current_page === 'contact' ? 'class="active"' : ''; ?>>Contact</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin/dashboard.php" <?php echo strpos($current_page, 'dashboard') !== false ? 'class="active"' : ''; ?>>Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="profile.php" <?php echo $current_page === 'profile' ? 'class="active"' : ''; ?>>Profile</a></li>
                        <li><a href="auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php" <?php echo $current_page === 'login' ? 'class="active"' : ''; ?>>Login</a></li>
                        <li><a href="auth/register.php" <?php echo $current_page === 'register' ? 'class="active"' : ''; ?>>Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header> -->



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
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <a href="<?php echo $base_url; ?>" class="logo">MyBlog</a>
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
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="<?php echo $base_url; ?>admin/dashboard.php" <?php echo strpos($current_page, 'dashboard') !== false ? 'class="active"' : ''; ?>>Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo $base_url; ?>profile.php" <?php echo $current_page === 'profile' ? 'class="active"' : ''; ?>>Profile</a></li>
                        <li><a href="<?php echo $base_url; ?>auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a style="color: red; border: 1px solid blue; border-radius: 5px; padding: 5px; background-color:blue; color:white; text-decoration: none; hover: border: 1px solid blue" href="<?php echo $base_url; ?>auth/login.php" >Login</a></li>
                        
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>