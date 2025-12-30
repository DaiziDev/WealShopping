<?php
// customer/auth_check.php
require_once '../includes/config.php';

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with return URL
    $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
    header('Location: ' . site_url('pages/login.php'));
    exit();
}

// Check if user has customer role (not admin)
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'customer') {
    // Admins should be redirected to admin panel
    header('Location: ' . site_url('admin/index.php'));
    exit();
}

// Get current user details
$current_user = getUserById($_SESSION['user_id']);

// Check if user exists and is active
if (!$current_user) {
    // User doesn't exist in database, logout
    session_destroy();
    header('Location: ' . site_url('pages/login.php'));
    exit();
}

// Function to check if current page is active
function isActivePage($page_name) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return $current_page === $page_name ? 'active' : '';
}
?>