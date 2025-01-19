<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header('Location: /auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

if ($id) {
    $conn = getConnection();
    
    // Verify comment belongs to author's post
    $stmt = $conn->prepare("
        UPDATE comments c 
        JOIN posts p ON c.post_id = p.id 
        SET c.status = 'pending' 
        WHERE c.id = ? AND p.user_id = ?
    ");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
}

header('Location: index.php');
exit();