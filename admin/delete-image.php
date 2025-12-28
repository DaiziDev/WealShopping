<?php
// admin/delete-image.php
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
        // Get image info
        $sql = "SELECT image_url FROM product_images WHERE id = ? AND product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$image_id, $product_id]);
        $image = $stmt->fetch();
        
        if ($image) {
            // Delete from database
            $sql = "DELETE FROM product_images WHERE id = ? AND product_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$image_id, $product_id]);
            
            // Delete physical file
            $file_path = '../../' . $image['image_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            echo json_encode(['success' => true, 'message' => 'Image deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Image not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>