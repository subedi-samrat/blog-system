<!-- admin/posts/delete.php -->
<?php
session_start();
require_once '../../config/database.php';

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    $conn = getConnection();
    
    // Get post image before deletion
    $stmt = $conn->prepare("SELECT featured_image FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();
    
    // Delete post
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete associated image if exists
        if ($post && $post['featured_image'] && file_exists('../../' . $post['featured_image'])) {
            unlink('../../' . $post['featured_image']);
        }
        
        // Delete associated comments
        $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Delete post-tag relationships
        $stmt = $conn->prepare("DELETE FROM post_tags WHERE post_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

header('Location: index.php');
exit();