<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE comments SET status = 'spam' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: index.php');
exit();
