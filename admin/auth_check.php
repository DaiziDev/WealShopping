<?php
// admin/auth_check.php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only include config if not already included
if (!isset($pdo)) {
    require_once '../includes/config.php';
}
require_once '../includes/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Get admin info
$admin_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();
?>