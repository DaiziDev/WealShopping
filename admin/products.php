<?php
require_once '../includes/config.php';

if (!isAdmin()) {
    header('Location: ../pages/login.php');
    exit();
}

// Handle product actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'add':
            // Handle product addition
            break;
        case 'edit':
            // Handle product editing
            break;
        case 'delete':
            if (isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $sql = "UPDATE products SET is_active = 0 WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id]);
            }
            break;
    }
}

// Get all products
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";
$products = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - <?php echo SITE_NAME; ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Add admin styles from previous admin dashboard */
        /* ... */
    </style>
</head>
<body>
    <!-- Sidebar (same as index.php) -->
    <!-- ... -->
    
    <div class="main-content">
        <div class="header">
            <h1>Manage Products</h1>
            <a href="product-add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
        
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td>
                            <?php 
                            $image_sql = "SELECT image_url FROM product_images WHERE product_id = ? AND is_main = 1 LIMIT 1";
                            $image_stmt = $pdo->prepare($image_sql);
                            $image_stmt->execute([$product['id']]);
                            $image = $image_stmt->fetch();
                            ?>
                            <img src="<?php echo $image['image_url'] ?? '../assets/images/still-life-rendering-jackets-display.jpg'; ?>" 
                                 alt="<?php echo $product['name']; ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        </td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['category_name'] ?? 'Uncategorized'; ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td>
                            <?php if ($product['is_active']): ?>
                            <span class="status active">Active</span>
                            <?php else: ?>
                            <span class="status inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>