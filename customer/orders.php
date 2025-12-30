<?php
// customer/orders.php
require_once 'auth_check.php';

// Handle order status filter
$status = $_GET['status'] ?? 'all';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$whereClause = "WHERE user_id = ?";
$params = [$current_user['id']];

if ($status !== 'all') {
    $whereClause .= " AND order_status = ?";
    $params[] = $status;
}

// Get total orders count
$sql = "SELECT COUNT(*) as total FROM orders $whereClause";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$total_orders = $stmt->fetch()['total'];
$total_pages = ceil($total_orders / $limit);

// Get orders with pagination
$sql = "SELECT * FROM orders $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
$fullParams = array_merge($params, [$limit, $offset]);
$stmt = $pdo->prepare($sql);
$stmt->execute($fullParams);
$orders = $stmt->fetchAll();

// Get order items for display
function getOrderItems($order_id) {
    global $pdo;
    $sql = "SELECT * FROM order_items WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - <?php echo SITE_NAME; ?></title>
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
                <!-- Orders Header -->
                <div class="customer-header">
                    <h1>My Orders</h1>
                    <p>Track and manage your orders</p>
                </div>

                <!-- Status Filter -->
                <div class="filter-tabs">
                    <a href="?status=all" class="filter-tab <?php echo $status == 'all' ? 'active' : ''; ?>">
                        All (<?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
                            $stmt->execute([$current_user['id']]);
                            echo $stmt->fetchColumn();
                        ?>)
                    </a>
                    <a href="?status=pending" class="filter-tab <?php echo $status == 'pending' ? 'active' : ''; ?>">
                        Pending (<?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status = 'pending'");
                            $stmt->execute([$current_user['id']]);
                            echo $stmt->fetchColumn();
                        ?>)
                    </a>
                    <a href="?status=processing" class="filter-tab <?php echo $status == 'processing' ? 'active' : ''; ?>">
                        Processing (<?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status = 'processing'");
                            $stmt->execute([$current_user['id']]);
                            echo $stmt->fetchColumn();
                        ?>)
                    </a>
                    <a href="?status=shipped" class="filter-tab <?php echo $status == 'shipped' ? 'active' : ''; ?>">
                        Shipped (<?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status = 'shipped'");
                            $stmt->execute([$current_user['id']]);
                            echo $stmt->fetchColumn();
                        ?>)
                    </a>
                    <a href="?status=delivered" class="filter-tab <?php echo $status == 'delivered' ? 'active' : ''; ?>">
                        Delivered (<?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status = 'delivered'");
                            $stmt->execute([$current_user['id']]);
                            echo $stmt->fetchColumn();
                        ?>)
                    </a>
                </div>

                <!-- Orders List -->
                <div class="section-card">
                    <?php if (count($orders) > 0): ?>
                        <div class="orders-list">
                            <?php foreach($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-meta">
                                        <div class="order-id">Order #<?php echo htmlspecialchars($order['order_number']); ?></div>
                                        <div class="order-date">Placed on <?php echo date('F d, Y', strtotime($order['created_at'])); ?></div>
                                    </div>
                                    <div class="order-status-badge">
                                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                        <?php if ($order['tracking_number']): ?>
                                        <span class="tracking-number">
                                            <i class="fas fa-truck"></i> Tracking: <?php echo $order['tracking_number']; ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="order-items">
                                    <?php 
                                    $items = getOrderItems($order['id']);
                                    foreach($items as $item): 
                                    ?>
                                    <div class="order-item">
                                        <div class="item-info">
                                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                            <div class="item-qty">Quantity: <?php echo $item['quantity']; ?></div>
                                        </div>
                                        <div class="item-price"><?php echo format_price($item['total_price']); ?></div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="order-footer">
                                    <div class="order-total">
                                        <strong>Total: <?php echo format_price($order['total_amount']); ?></strong>
                                    </div>
                                    <div class="order-actions">
                                        <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <?php if ($order['order_status'] == 'pending'): ?>
                                        <button class="btn btn-danger btn-sm" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-times"></i> Cancel Order
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=1&status=<?php echo $status; ?>" class="page-link">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                                <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status; ?>" class="page-link">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&status=<?php echo $status; ?>" 
                                   class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status; ?>" class="page-link">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                                <a href="?page=<?php echo $total_pages; ?>&status=<?php echo $status; ?>" class="page-link">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>No orders found</h3>
                            <p><?php echo $status !== 'all' ? "You don't have any $status orders." : "You haven't placed any orders yet."; ?></p>
                            <a href="<?php echo site_url('pages/shop.php'); ?>" class="btn btn-primary">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
                window.location.href = 'order-actions.php?action=cancel&id=' + orderId;
            }
        }
        
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