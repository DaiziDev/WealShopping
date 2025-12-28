<?php
// admin/index.php
require_once 'auth_check.php';

// Get dashboard statistics
$stats = [];

// Get total products
$sql = "SELECT COUNT(*) as total FROM products WHERE is_active = 1";
$stmt = $pdo->query($sql);
$stats['total_products'] = $stmt->fetch()['total'];

// Get total orders
$sql = "SELECT COUNT(*) as total FROM orders";
$stmt = $pdo->query($sql);
$stats['total_orders'] = $stmt->fetch()['total'];

// Get total customers
$sql = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$stmt = $pdo->query($sql);
$stats['total_customers'] = $stmt->fetch()['total'];

// Get recent orders
$sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 10";
$stmt = $pdo->query($sql);
$recent_orders = $stmt->fetchAll();

// Get low stock products
$sql = "SELECT p.*, pi.image_url 
        FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE p.quantity <= p.low_stock_threshold AND p.is_active = 1 
        ORDER BY p.quantity ASC LIMIT 10";
$stmt = $pdo->query($sql);
$low_stock_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WealShopping</title>
    <!-- Use external CSS file -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="admin-main">
            <!-- Dashboard Header -->
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div>
                    <small>Welcome back, Admin!</small>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #4361ee;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3><?php echo $stats['total_orders']; ?></h3>
                    <p>Total Orders</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #4CAF50;">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3><?php echo $stats['total_products']; ?></h3>
                    <p>Products</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #FF9800;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3><?php echo $stats['total_customers']; ?></h3>
                    <p>Customers</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #9C27B0;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>$0.00</h3>
                    <p>Revenue</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Recent Orders</h2>
                    <a href="orders.php" class="btn btn-primary btn-sm">View All</a>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_orders) > 0): ?>
                                <?php foreach($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">
                                        No orders found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Low Stock Products</h2>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Threshold</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($low_stock_products) > 0): ?>
                                <?php foreach($low_stock_products as $product): ?>
                                <tr>
                                    <td>
                                        <?php if ($product['image_url']): ?>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                            <span><?php echo htmlspecialchars($product['name']); ?></span>
                                        </div>
                                        <?php else: ?>
                                        <?php echo htmlspecialchars($product['name']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                    <td><?php echo $product['quantity']; ?></td>
                                    <td><?php echo $product['low_stock_threshold']; ?></td>
                                    <td>
                                        <span class="status-badge" style="background: #ffc107; color: #000;">
                                            Low Stock
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">
                                        No low stock products
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Add active class to current page in sidebar
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
            
            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>