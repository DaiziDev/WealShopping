<?php
// admin/set-main-image.php
require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $image_id = intval($_POST['image_id']);
    
    try {
        // First, set all images of this product to not main
        $sql = "UPDATE product_images SET is_main = 0 WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        
        // Then set the selected image as main
        $sql = "UPDATE product_images SET is_main = 1 WHERE id = ? AND product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$image_id, $product_id]);
        
        echo json_encode(['success' => true, 'message' => 'Main image updated']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>