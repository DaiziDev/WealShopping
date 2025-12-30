<?php
// customer/logout.php
require_once '../includes/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data
session_destroy();

// Clear any cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Redirect to login page
header('Location: ' . site_url('pages/login.php'));
exit();
?>