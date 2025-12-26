<?php
// CSRF Protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Add CSRF token to forms
function csrfField() {
    $token = generateCSRFToken();
    echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

// Validate CSRF token on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }
}
?>