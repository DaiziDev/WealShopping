<?php
require_once 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if it's an AJAX request
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Initialize response array
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? sanitize($_POST['product_name']) : '';
    $product_price = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0;
    $image_url = isset($_POST['image_url']) ? sanitize($_POST['image_url']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    // Debug logging
    error_log("Add to cart request: product_id=$product_id, product_name=$product_name, price=$product_price");
    
    // Validate product data
    if ($product_id > 0 && !empty($product_name) && $product_price > 0) {
        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Check if product already exists in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) { // Changed from 'product_id' to 'id'
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        
        // If not found, add new item WITH CORRECT KEYS
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product_id, // Changed to 'id' to match cart.php
                'name' => $product_name, // Changed to 'name' to match cart.php
                'price' => $product_price, // Already correct
                'image' => $image_url, // Changed to 'image' to match cart.php
                'quantity' => $quantity // Already correct
            ];
        }
        
        // Calculate cart statistics
        $cart_count = getCartCount();
        $cart_total = getCartTotal();
        
        $response = [
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cart_count,
            'cart_total' => $cart_total,
            'cart_total_formatted' => format_price($cart_total)
        ];
        
        error_log("Cart add success: count=$cart_count, total=$cart_total");
    } else {
        $response = [
            'success' => false,
            'message' => 'Invalid product data. Please try again.',
            'debug' => [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price
            ]
        ];
        error_log("Cart add failed: Invalid data");
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request (for direct links)
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
    
    if ($product_id > 0) {
        // Get product from database
        $sql = "SELECT * FROM products WHERE id = ? AND is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Initialize cart if not exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Get product image
            $image_sql = "SELECT image_url FROM product_images WHERE product_id = ? AND is_main = 1 LIMIT 1";
            $image_stmt = $pdo->prepare($image_sql);
            $image_stmt->execute([$product_id]);
            $image = $image_stmt->fetch();
            $image_url = $image ? $image['image_url'] : '';
            
            // Check if product already exists in cart
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) { // Changed from 'product_id' to 'id'
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            
            // If not found, add new item WITH CORRECT KEYS
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'], // Changed to 'id'
                    'name' => $product['name'], // Changed to 'name'
                    'price' => $product['price'],
                    'image' => $image_url, // Changed to 'image'
                    'quantity' => $quantity
                ];
            }
            
            $_SESSION['success'] = $product['name'] . ' added to cart!';
        } else {
            $_SESSION['error'] = 'Product not found';
        }
    }
    
    // Redirect back
    $return_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('pages/shop.php');
    header('Location: ' . $return_url);
    exit;
}
?>