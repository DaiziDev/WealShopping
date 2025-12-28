<?php
// includes/config.php

// Include error reporting
require_once 'error_reporting.php';

// Fix: Check if session is already started before starting it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'elitestyle_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// Dynamically determine site URL - SIMPLIFIED VERSION
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Get the current script directory
$script_dir = dirname($_SERVER['SCRIPT_NAME']);

// For localhost development
if (strpos($host, 'localhost') !== false) {
    // Get project folder name
    $project_folder = basename(dirname(__DIR__)); // Go up one level from includes/
    define('SITE_URL', "{$protocol}://{$host}/{$project_folder}/");
    // Define absolute path for file operations
    define('ROOT_PATH', dirname(__DIR__) . '/');
} else {
    // For live server
    define('SITE_URL', "{$protocol}://{$host}{$script_dir}/");
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . $script_dir . '/');
}

// Site configuration
define('SITE_NAME', 'WealShopping');
define('ADMIN_EMAIL', 'admin@wealshopping.com');

// Currency configuration for Cameroon
if (!defined('CURRENCY')) {
    define('CURRENCY', 'FCFA');
    define('CURRENCY_SYMBOL', 'FCFA');
    define('CURRENCY_CODE', 'XAF');
}

// Payment configuration
if (!defined('MTN_MOBILE_MONEY')) {
    define('MTN_MOBILE_MONEY', '+237 6XX XXX XXX');
}

if (!defined('ORANGE_MONEY')) {
    define('ORANGE_MONEY', '+237 6XX XXX XXX');
}

// Image upload configuration
define('UPLOAD_PATH', 'assets/images/products/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to format price in FCFA
if (!function_exists('format_price')) {
    function format_price($amount) {
        return number_format($amount, 0, '', ' ') . ' ' . CURRENCY_SYMBOL;
    }
}

// Function to get price with FCFA symbol
if (!function_exists('price_with_currency')) {
    function price_with_currency($amount) {
        return format_price($amount);
    }
}

// Function to get absolute URL for assets - UPDATED
function asset_url($path) {
    return SITE_URL . 'assets/' . ltrim($path, '/');
}

// Function to get URL for pages
function page_url($page) {
    return SITE_URL . 'pages/' . ltrim($page, '/');
}

// Function to get site URL
function site_url($path = '') {
    return SITE_URL . ltrim($path, '/');
}

// Function to get featured products
function getFeaturedProducts($limit = 8) {
    global $pdo;
    
    $sql = "SELECT p.*, pi.image_url 
            FROM products p 
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
            WHERE p.featured = 1 AND p.is_active = 1 AND p.quantity > 0
            ORDER BY p.created_at DESC 
            LIMIT " . (int)$limit;
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Function to get product by slug
function getProductBySlug($slug) {
    global $pdo;
    
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.is_active = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Function to get product images
function getProductImages($product_id) {
    global $pdo;
    
    $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order, is_main DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    return $stmt->fetchAll();
}

// Function to get product attributes
function getProductAttributes($product_id) {
    global $pdo;
    
    $sql = "SELECT * FROM product_attributes WHERE product_id = ? ORDER BY id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    return $stmt->fetchAll();
}

// Function to sanitize input
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Function to generate order number
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
}

// CORRECTED FUNCTION: Get product image URL - SIMPLIFIED VERSION
function get_product_image_url($image_path, $return_default = true) {
    // If empty and we want default
    if (empty($image_path) && $return_default) {
        return SITE_URL . 'assets/images/still-life-rendering-jackets-display.jpg';
    }
    
    // If already a full URL (from external sources)
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    
    // If it starts with assets/, it's already a relative path from root
    if (strpos($image_path, 'assets/') === 0) {
        return SITE_URL . $image_path;
    }
    
    // If it starts with uploads/ (common for uploaded files)
    if (strpos($image_path, 'uploads/') === 0) {
        return SITE_URL . $image_path;
    }
    
    // For product images in the database
    if (strpos($image_path, 'products/') !== false) {
        return SITE_URL . 'assets/images/' . $image_path;
    }
    
    // Default: assume it's in the products folder
    return SITE_URL . 'assets/images/products/' . $image_path;
}

// NEW FUNCTION: Upload image and return relative path
function upload_product_image($file, $product_id = 0) {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Upload error: " . $file['error'];
        return ['success' => false, 'errors' => $errors];
    }
    
    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        $errors[] = "File is too large. Maximum size is 5MB.";
        return ['success' => false, 'errors' => $errors];
    }
    
    // Check file type
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, ALLOWED_IMAGE_TYPES)) {
        $errors[] = "Only JPG, PNG, GIF, and WebP files are allowed.";
        return ['success' => false, 'errors' => $errors];
    }
    
    // Generate unique filename
    $filename = 'product_' . ($product_id ? $product_id . '_' : '') . time() . '_' . uniqid() . '.' . $file_ext;
    $upload_dir = ROOT_PATH . UPLOAD_PATH;
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Move uploaded file
    $destination = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Return relative path (without ROOT_PATH)
        $relative_path = UPLOAD_PATH . $filename;
        return [
            'success' => true,
            'path' => $relative_path,
            'url' => SITE_URL . $relative_path,
            'filename' => $filename
        ];
    } else {
        $errors[] = "Failed to move uploaded file.";
        return ['success' => false, 'errors' => $errors];
    }
}

// NEW FUNCTION: Delete image file
function delete_image_file($image_path) {
    if (empty($image_path)) {
        return true;
    }
    
    // Extract filename from URL if it's a full URL
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        $image_path = str_replace(SITE_URL, '', $image_path);
    }
    
    $full_path = ROOT_PATH . $image_path;
    
    if (file_exists($full_path) && is_file($full_path)) {
        return unlink($full_path);
    }
    
    return true;
}

// Function to get categories
function getCategories($parent_id = null) {
    global $pdo;
    
    $sql = "SELECT * FROM categories WHERE is_active = 1";
    if ($parent_id === null) {
        $sql .= " AND parent_id IS NULL";
    } else {
        $sql .= " AND parent_id = ?";
    }
    $sql .= " ORDER BY name";
    
    $stmt = $pdo->prepare($sql);
    if ($parent_id === null) {
        $stmt->execute();
    } else {
        $stmt->execute([$parent_id]);
    }
    
    return $stmt->fetchAll();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate cart total
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// Get cart count
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

// Function to get products with pagination
function getProducts($category_slug = '', $search = '', $min_price = 0, $max_price = 10000000, $sort = 'newest', $limit = 12, $offset = 0) {
    global $pdo;
    
    $sql = "SELECT p.*, pi.image_url 
            FROM products p 
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
            WHERE p.is_active = 1 AND p.quantity > 0";
    
    $params = [];
    
    if (!empty($category_slug)) {
        $sql .= " AND p.category_id IN (SELECT id FROM categories WHERE slug = ?)";
        $params[] = $category_slug;
    }
    
    if (!empty($search)) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.short_description LIKE ?)";
        $search_term = "%$search%";
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
    }
    
    $sql .= " AND p.price BETWEEN ? AND ?";
    $params[] = $min_price;
    $params[] = $max_price;
    
    // Sorting
    switch ($sort) {
        case 'price_low':
            $sql .= " ORDER BY p.price ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY p.price DESC";
            break;
        case 'name':
            $sql .= " ORDER BY p.name ASC";
            break;
        default:
            $sql .= " ORDER BY p.created_at DESC";
    }
    
    // Add pagination
    $sql .= " LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Count total products for pagination
function countProducts($category_slug = '', $search = '', $min_price = 0, $max_price = 10000000) {
    global $pdo;
    
    $sql = "SELECT COUNT(*) as total FROM products p WHERE p.is_active = 1 AND p.quantity > 0";
    $params = [];
    
    if (!empty($category_slug)) {
        $sql .= " AND p.category_id IN (SELECT id FROM categories WHERE slug = ?)";
        $params[] = $category_slug;
    }
    
    if (!empty($search)) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.short_description LIKE ?)";
        $search_term = "%$search%";
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
    }
    
    $sql .= " AND p.price BETWEEN ? AND ?";
    $params[] = $min_price;
    $params[] = $max_price;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result['total'];
}

// Function to get user by ID
function getUserById($user_id) {
    global $pdo;
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}
?>