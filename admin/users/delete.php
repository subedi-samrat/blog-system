<!-- admin/users/delete.php -->
<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prevent self-deletion
if ($id == $_SESSION['user_id']) {
    header('Location: index.php');
    exit();
}

if ($id) {
    $conn = getConnection();
    
    // Delete user's posts
    $stmt = $conn->prepare("DELETE FROM posts WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Delete user's comments
    $stmt = $conn->prepare("DELETE FROM comments WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: index.php');
exit();