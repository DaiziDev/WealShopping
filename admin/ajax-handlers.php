<?php
// admin/ajax-handlers.php
require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Set content type
header('Content-Type: application/json');

// Handle set main image
if (isset($_POST['set_main']) && isset($_POST['image_id']) && isset($_POST['product_id'])) {
    $image_id = intval($_POST['image_id']);
    $product_id = intval($_POST['product_id']);
    
    try {
        // First, set all images of this product as not main
        $sql = "UPDATE product_images SET is_main = 0 WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        
        // Then set the selected image as main
        $sql = "UPDATE product_images SET is_main = 1 WHERE id = ? AND product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$image_id, $product_id]);
        
        echo json_encode(['success' => true, 'message' => 'Main image updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit();
}

// Handle delete image
if (isset($_POST['delete_image']) && isset($_POST['image_id']) && isset($_POST['product_id'])) {
    $image_id = intval($_POST['image_id']);
    $product_id = intval($_POST['product_id']);
    
    try {
        // Get image URL before deleting
        $sql = "SELECT image_url FROM product_images WHERE id = ? AND product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$image_id, $product_id]);
        $image = $stmt->fetch();
        
        if ($image) {
            // Delete the image file
            delete_image_file($image['image_url']);
            
            // Delete from database
            $sql = "DELETE FROM product_images WHERE id = ? AND product_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$image_id, $product_id]);
            
            // Check if we need to set a new main image
            $sql = "SELECT COUNT(*) as count FROM product_images WHERE product_id = ? AND is_main = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$product_id]);
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                // Set the first image as main
                $sql = "UPDATE product_images SET is_main = 1 WHERE product_id = ? LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$product_id]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Image not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit();
}

// Add this to your existing ajax-handlers.php file
if (isset($_GET['action']) && $_GET['action'] == 'get_customer') {
    require_once 'auth_check.php';
    
    $customer_id = $_GET['id'] ?? 0;
    
    if ($customer_id) {
        // Get customer details
        $sql = "SELECT *, CONCAT(first_name, ' ', last_name) as full_name FROM users WHERE id = ? AND role = 'customer'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$customer_id]);
        $customer = $stmt->fetch();
        
        if ($customer) {
            // Get initials
            $initials = '';
            if ($customer['first_name'] && $customer['last_name']) {
                $initials = substr($customer['first_name'], 0, 1) . substr($customer['last_name'], 0, 1);
            } elseif ($customer['username']) {
                $initials = substr($customer['username'], 0, 2);
            } else {
                $initials = substr($customer['email'], 0, 2);
            }
            $customer['initials'] = strtoupper($initials);
            
            // Get order count and total
            $sql = "SELECT COUNT(*) as order_count, SUM(total_amount) as total_spent FROM orders WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id]);
            $order_info = $stmt->fetch();
            
            $customer['order_count'] = $order_info['order_count'] ?? 0;
            $customer['total_spent'] = number_format($order_info['total_spent'] ?? 0, 2);
            $customer['join_date'] = date('F d, Y \a\t H:i', strtotime($customer['created_at']));
            
            // Get recent orders
            $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id]);
            $orders = $stmt->fetchAll();
            
            foreach ($orders as &$order) {
                $order['date'] = date('M d, Y', strtotime($order['created_at']));
                $order['total'] = number_format($order['total_amount'], 2);
            }
            
            echo json_encode([
                'success' => true,
                'customer' => $customer,
                'orders' => $orders
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Customer not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid customer ID']);
    }
    exit;
}

// Add export functionality (optional)
if (isset($_GET['action']) && $_GET['action'] == 'export_customers') {
    require_once 'auth_check.php';
    
    $search = $_GET['search'] ?? '';
    
    // Build WHERE clause
    $whereClause = "WHERE role = 'customer'";
    $params = [];
    if ($search) {
        $whereClause .= " AND (username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
        $searchTerm = "%{$search}%";
        $params = array_fill(0, 4, $searchTerm);
    }
    
    // Get all customers
    $sql = "SELECT * FROM users $whereClause ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $customers = $stmt->fetchAll();
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=customers_' . date('Y-m-d') . '.csv');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Username', 'Phone', 'Address', 'City', 'State', 'Zip Code', 'Country', 'Joined Date']);
    
    // Add data rows
    foreach ($customers as $customer) {
        fputcsv($output, [
            $customer['id'],
            $customer['first_name'],
            $customer['last_name'],
            $customer['email'],
            $customer['username'],
            $customer['phone'],
            $customer['address'],
            $customer['city'],
            $customer['state'],
            $customer['zip_code'],
            $customer['country'],
            $customer['created_at']
        ]);
    }
    
    fclose($output);
    exit;
}

// If no valid action
echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>