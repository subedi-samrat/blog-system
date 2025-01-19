<!-- // auth/login.php -->
<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header('Location: ../admin/dashboard.php');
            } elseif ($user['role'] == 'author') {
                header('Location: ../author/dashboard.php');
            } else {
                header('Location: ../index.php');
            }
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyBlog</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Login to MyBlog</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Login</button>
            </form>

            <p class="auth-links">
                Don't have an account? <a href="register.php">Register</a>
                <br>
                <!-- <a href="forgot-password.php">Forgot Password?</a> -->
            </p>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    
</body>

</html>