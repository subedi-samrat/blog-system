<!-- admin/users/edit.php -->
<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    
    // Check if password should be updated
    $password_sql = '';
    $types = "sssi";
    $params = [$username, $email, $full_name, $id];
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = ?";
        $types = "ssssi";
        $params = [$username, $email, $full_name, $password, $id];
    }
    
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, full_name = ?" . $password_sql . " WHERE id = ?");
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    }
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
    <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <h1>Edit User</h1>
            
            <form method="POST" class="post-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password">
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="author" <?php echo $user['role'] == 'author' ? 'selected' : ''; ?>>Author</option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Update User</button>
            </form>
        </main>
    </div>
</body>
</html>