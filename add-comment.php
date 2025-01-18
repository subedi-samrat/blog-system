<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = (int)$_POST['post_id'];
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($content)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    
    if ($stmt->execute()) {
        // Optional: Set success message in session
        $_SESSION['comment_success'] = true;
    }
}

// Redirect back to the post
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();