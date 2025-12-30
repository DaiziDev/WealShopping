<?php
// customer/dashboard.php
require_once 'auth_check.php';

// Get customer statistics
$stats = [];

// Get total orders
$sql = "SELECT COUNT(*) as total, SUM(total_amount) as total_spent FROM orders WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_user['id']]);
$order_stats = $stmt->fetch();
$stats['total_orders'] = $order_stats['total'] ?? 0;
$stats['total_spent'] = $order_stats['total_spent'] ?? 0;

// Get pending orders
$sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND order_status IN ('pending', 'processing')";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_user['id']]);
$stats['pending_orders'] = $stmt->fetch()['total'] ?? 0;

// Get wishlist items count
$sql = "SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_user['id']]);
$stats['wishlist_items'] = $stmt->fetch()['total'] ?? 0;

// Get recent orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_user['id']]);
$recent_orders = $stmt->fetchAll();

// Get wishlist items
$sql = "SELECT w.*, p.name, p.price, p.slug, pi.image_url 
        FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE w.user_id = ? AND p.is_active = 1
        LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_user['id']]);
$wishlist_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/customer.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="customer-container">
        <div class="customer-layout">
            <?php include 'sidebar.php'; ?>
            
            <main class="customer-main">
                <!-- Welcome Header -->
                <div class="customer-header">
                    <h1>Welcome back, <?php echo htmlspecialchars($current_user['first_name']); ?>!</h1>
                    <p>Here's what's happening with your account.</p>
                </div>

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
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3><?php echo format_price($stats['total_spent']); ?></h3>
                        <p>Total Spent</p>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #FF9800;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3><?php echo $stats['pending_orders']; ?></h3>
                        <p>Pending Orders</p>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #9C27B0;">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3><?php echo $stats['wishlist_items']; ?></h3>
                        <p>Wishlist Items</p>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="section-card">
                    <div class="section-header">
                        <h3>Recent Orders</h3>
                        <a href="orders.php" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    
                    <?php if (count($recent_orders) > 0): ?>
                        <div class="orders-list">
                            <?php foreach($recent_orders as $order): ?>
                            <div class="order-item">
                                <div class="order-info">
                                    <div class="order-id">Order #<?php echo htmlspecialchars($order['order_number']); ?></div>
                                    <div class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                                </div>
                                <div class="order-details">
                                    <div class="order-amount"><?php echo format_price($order['total_amount']); ?></div>
                                    <span class="order-status status-<?php echo $order['order_status']; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </div>
                                <div class="order-actions">
                                    <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <p>You haven't placed any orders yet.</p>
                            <a href="<?php echo site_url('pages/shop.php'); ?>" class="btn btn-primary">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Wishlist Items -->
                <div class="section-card">
                    <div class="section-header">
                        <h3>Wishlist Items</h3>
                        <a href="wishlist.php" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    
                    <?php if (count($wishlist_items) > 0): ?>
                        <div class="products-grid">
                            <?php foreach($wishlist_items as $item): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="<?php echo site_url('pages/product-detail.php?slug=' . $item['slug']); ?>">
                                        <img src="<?php echo get_product_image_url($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </a>
                                </div>
                                <div class="product-info">
                                    <h4 class="product-title">
                                        <a href="<?php echo site_url('pages/product-detail.php?slug=' . $item['slug']); ?>">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    </h4>
                                    <div class="product-price"><?php echo format_price($item['price']); ?></div>
                                    <div class="product-actions">
                                        <a href="../includes/add-to-cart.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </a>
                                        <a href="wishlist.php?action=remove&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-heart"></i>
                            <p>Your wishlist is empty.</p>
                            <a href="<?php echo site_url('pages/shop.php'); ?>" class="btn btn-primary">Browse Products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Add active class to current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.customer-nav a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>