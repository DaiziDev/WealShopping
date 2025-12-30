<?php
// admin/delete-product.php
require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id === 0) {
    header('Location: products.php');
    exit();
}

try {
    // Get all images for this product
    $sql = "SELECT image_url FROM product_images WHERE product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $images = $stmt->fetchAll();
    
    // Delete image files
    foreach ($images as $image) {
        delete_image_file($image['image_url']);
    }
    
    // Delete from database (cascade will handle related records)
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    
    $_SESSION['success_message'] = "Product deleted successfully!";
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error deleting product: " . $e->getMessage();
}

header('Location: products.php');
exit();
?>