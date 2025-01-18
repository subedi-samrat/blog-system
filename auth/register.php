<!-- // auth/register.php -->
<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];

    $conn = getConnection();

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "Email already registered";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, 'user')");
        $stmt->bind_param("ssss", $username, $email, $password, $full_name);

        if ($stmt->execute()) {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit();
        } else {
            $error = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MyBlog</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Create Account</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>

                <button type="submit" class="btn-primary">Register</button>
            </form>

            <p class="auth-links">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>