<!-- // includes/functions.php -->
<?php
function slugify($text)
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\-]/', '-', $text);
    $text = preg_replace('/-+/', "-", $text);
    return trim($text, '-');
}

function uploadImage($file, $directory = 'uploads/')
{
    $target_dir = $directory;
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . '.' . $imageFileType;

    // Check if image file is valid
    $valid_types = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($imageFileType, $valid_types)) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}

// function getRecentPosts($limit = 5) {
//     $conn = getConnection();
//     $sql = "SELECT p.*, u.username, c.name as category_name 
//             FROM posts p 
//             LEFT JOIN users u ON p.user_id = u.id 
//             LEFT JOIN categories c ON p.category_id = c.id 
//             WHERE p.status = 'published' 
//             ORDER BY p.created_at DESC 
//             LIMIT ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $limit);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     return $result->fetch_all(MYSQLI_ASSOC);
// }

function getRecentPosts($limit = 6)
{
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT p.*, u.username 
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.id 
            WHERE p.status = 'published' 
            ORDER BY p.created_at DESC 
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// New required functions
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getUserById($id)
{
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    } catch (Exception $e) {
        return null;
    }
}

function formatDate($date, $format = 'M d, Y')
{
    return date($format, strtotime($date));
}

function sanitizeOutput($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function generateCsrfToken()
{
    try {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
    } catch (Exception $e) {
        return null;
    }
}

function verifyCsrfToken($token)
{
    try {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    } catch (Exception $e) {
        return false;
    }
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password)
{
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}

function getCategories()
{
    try {
    $conn = getConnection();
    return $conn->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function getTags()
{
    try{
    $conn = getConnection();
    return $conn->query("SELECT * FROM tags ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function getPostsByCategory($category_id, $limit = 10)
{
    try{
    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT p.*, u.username 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id 
        WHERE p.category_id = ? AND p.status = 'published' 
        ORDER BY p.created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("ii", $category_id, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function getCommentsByPost($post_id)
{
    try{
    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT c.*, u.username 
        FROM comments c 
        LEFT JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = ? AND c.status = 'approved' 
        ORDER BY c.created_at DESC
    ");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function createNotification($user_id, $type, $message, $link = '')
{
    try{
    $conn = getConnection();
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, type, message, link) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $user_id, $type, $message, $link);
    return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function getUserNotifications($user_id, $limit = 10)
{
    try{
    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function logActivity($user_id, $action, $details = '')
{
    try{
    $conn = getConnection();
    $stmt = $conn->prepare("
        INSERT INTO activity_log (user_id, action, details) 
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iss", $user_id, $action, $details);
    return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function getPaginatedPosts($page = 1, $per_page = 10)
{
    try{
    $conn = getConnection();
    $offset = ($page - 1) * $per_page;

    // Get total posts count
    $total = $conn->query("SELECT COUNT(*) as count FROM posts WHERE status = 'published'")->fetch_assoc()['count'];

    // Get posts for current page
    $stmt = $conn->prepare("
        SELECT p.*, u.username, c.name as category_name 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'published' 
        ORDER BY p.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $per_page, $offset);
    $stmt->execute();

    return [
        'posts' => $stmt->get_result()->fetch_all(MYSQLI_ASSOC),
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page
    ];
    } catch (Exception $e) {
        return [];
    }
}
