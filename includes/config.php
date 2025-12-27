<?php
// Include error reporting
require_once 'error_reporting.php';

session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'elitestyle_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// Dynamically determine site URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$base_path = dirname($script);

// Remove any double slashes and ensure it ends with /
$base_path = rtrim($base_path, '/') . '/';
$base_path = str_replace('//', '/', $base_path);

// For localhost, we need to adjust the path
if (strpos($host, 'localhost') !== false) {
    // Get the project folder name
    $project_folder = basename(dirname(__FILE__, 2)); // Go up 2 levels from includes/
    define('SITE_URL', "{$protocol}://{$host}/{$project_folder}/");
} else {
    define('SITE_URL', "{$protocol}://{$host}{$base_path}");
}

// Site configuration
define('SITE_NAME', 'WealShopping');
define('ADMIN_EMAIL', 'admin@wealshopping.com');

// Payment configuration for Cameroon
define('MTN_MOBILE_MONEY', '+237 6XX XXX XXX');
define('ORANGE_MONEY', '+237 6XX XXX XXX');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
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
// Function to get absolute URL for assets
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
// Function to get featured products - FIXED VERSION
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

// Simple function to get products with pagination
function getProducts($category_slug = '', $search = '', $min_price = 0, $max_price = 1000, $sort = 'newest', $limit = 12, $offset = 0) {
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
function countProducts($category_slug = '', $search = '', $min_price = 0, $max_price = 1000) {
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