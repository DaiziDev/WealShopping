<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set timezone
date_default_timezone_set('Africa/Douala');

// Check if PDO extension is loaded
if (!extension_loaded('pdo')) {
    die('PDO extension is not loaded');
}

// Check if PDO MySQL driver is available
if (!in_array('mysql', PDO::getAvailableDrivers())) {
    die('PDO MySQL driver is not available');
}
?>