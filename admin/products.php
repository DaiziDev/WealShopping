<?php
// admin/products.php
require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Handle product deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = $_GET['delete'];
    
    // Check if product exists
    $sql = "SELECT pi.image_url FROM products p 
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
            WHERE p.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $image = $stmt->fetch();
    
    if ($image && !empty($image['image_url'])) {
        delete_image_file($image['image_url']);
    }
    
    // Instead of deleting, set inactive
    $sql = "UPDATE products SET is_active = 0 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    
    $_SESSION['success_message'] = "Product deactivated successfully!";
    header('Location: products.php');
    exit();
}

// Handle product activation
if (isset($_GET['activate']) && is_numeric($_GET['activate'])) {
    $product_id = $_GET['activate'];
    
    $sql = "UPDATE products SET is_active = 1 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    
    $_SESSION['success_message'] = "Product activated successfully!";
    header('Location: products.php');
    exit();
}

// Get filter parameters
$status = isset($_GET['status']) ? $_GET['status'] : 'active';
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query
$sql = "SELECT p.*, c.name as category_name, 
               (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";
$params = [];

// Apply filters
if ($status == 'active') {
    $sql .= " AND p.is_active = 1";
} elseif ($status == 'inactive') {
    $sql .= " AND p.is_active = 0";
}

if ($category_id > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if (!empty($search)) {
    $sql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$sql .= " ORDER BY p.created_at DESC";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for filter
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - WealShopping Admin</title>
    <link rel="stylesheet" href="<?php echo asset_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-image-small {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            overflow: hidden;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
        }
        
        .product-image-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 5px;
        }
        
        .badge-featured {
            background: #ffc107;
            color: #000;
        }
        
        .badge-low-stock {
            background: #dc3545;
            color: white;
        }
        
        .badge-active {
            background: #28a745;
            color: white;
        }
        
        .badge-inactive {
            background: #6c757d;
            color: white;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        .no-image {
            width: 60px;
            height: 60px;
            background: #f0f0f0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            border: 1px solid #ddd;
        }
        
        .product-info-cell {
            max-width: 250px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="admin-main">
            <header class="admin-header">
                <h1>Manage Products</h1>
                <div>
                    <a href="add-product.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            </header>

            <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="all" <?php echo $status == 'all' ? 'selected' : ''; ?>>All Products</option>
                            <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active Only</option>
                            <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive Only</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="0">All Categories</option>
                            <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="products.php" class="btn">Reset</a>
                    </div>
                </form>
            </div>

            <div class="content-section">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th class="product-info-cell">Product Details</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                            <?php 
                            $stock_class = '';
                            if ($product['quantity'] <= 0) {
                                $stock_class = 'text-danger';
                            } elseif ($product['quantity'] <= $product['low_stock_threshold']) {
                                $stock_class = 'text-warning';
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['main_image'])): ?>
                                    <div class="product-image-small">
                                        <img src="<?php echo get_product_image_url($product['main_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             onerror="this.onerror=null; this.src='<?php echo SITE_URL; ?>assets/images/still-life-rendering-jackets-display.jpg';">
                                    </div>
                                    <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="product-info-cell">
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                    <div style="font-size: 0.85rem; color: #666; margin-top: 5px;">
                                        <?php echo substr(htmlspecialchars($product['short_description']), 0, 50); ?>...
                                    </div>
                                    <?php if ($product['featured']): ?>
                                    <div><span class="badge badge-featured">Featured</span></div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                                <td>
                                    <strong><?php echo format_price($product['price']); ?></strong>
                                    <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                    <br><small style="text-decoration: line-through; color: #999;"><?php echo format_price($product['compare_price']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="<?php echo $stock_class; ?>">
                                    <?php echo $product['quantity']; ?>
                                    <?php if ($product['quantity'] <= $product['low_stock_threshold'] && $product['quantity'] > 0): ?>
                                    <br><small class="badge badge-low-stock">Low Stock</small>
                                    <?php elseif ($product['quantity'] == 0): ?>
                                    <br><small class="badge badge-low-stock">Out of Stock</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($product['is_active']): ?>
                                    <span class="badge badge-active">Active</span>
                                    <?php else: ?>
                                    <span class="badge badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="../pages/product-detail.php?slug=<?php echo $product['slug']; ?>" class="btn btn-sm" target="_blank" title="View" style="background: #f8f9fa;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($product['is_active']): ?>
                                        <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" title="Deactivate" onclick="return confirm('Are you sure you want to deactivate this product?')">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                        <?php else: ?>
                                        <a href="?activate=<?php echo $product['id']; ?>" class="btn btn-sm btn-success" title="Activate">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (empty($products)): ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 20px; color: #ddd;"></i>
                        <h3>No products found</h3>
                        <p>Try changing your filters or add a new product.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <style>
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
    </style>
</body>
</html>