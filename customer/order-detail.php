<?php
// customer/order-detail.php
require_once 'auth_check.php';

$order_id = $_GET['id'] ?? 0;

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

// Get order details
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id, $current_user['id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Get order items
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Calculate total items
$total_items = 0;
foreach ($order_items as $item) {
    $total_items += $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order['order_number']; ?> - <?php echo SITE_NAME; ?></title>
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
                <!-- Order Header -->
                <div class="customer-header">
                    <div>
                        <h1>Order #<?php echo htmlspecialchars($order['order_number']); ?></h1>
                        <p>Placed on <?php echo date('F d, Y \a\t H:i', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div>
                        <span class="status-badge status-<?php echo $order['order_status']; ?> large">
                            <?php echo ucfirst($order['order_status']); ?>
                        </span>
                    </div>
                </div>

                <!-- Order Progress -->
                <div class="section-card">
                    <h3>Order Status</h3>
                    <div class="order-progress">
                        <?php
                        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                        $current_status = array_search($order['order_status'], $statuses);
                        ?>
                        <div class="progress-steps">
                            <?php foreach($statuses as $index => $status): ?>
                            <div class="progress-step <?php echo $index <= $current_status ? 'completed' : ''; ?>">
                                <div class="step-icon">
                                    <?php if ($index < $current_status): ?>
                                        <i class="fas fa-check"></i>
                                    <?php elseif ($index == $current_status): ?>
                                        <i class="fas fa-circle"></i>
                                    <?php else: ?>
                                        <i class="far fa-circle"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="step-label"><?php echo ucfirst($status); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if ($order['tracking_number']): ?>
                        <div class="tracking-info">
                            <i class="fas fa-truck"></i>
                            <strong>Tracking Number:</strong> <?php echo $order['tracking_number']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="section-card">
                    <h3>Order Items (<?php echo $total_items; ?> items)</h3>
                    <div class="order-items-detail">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                            <?php 
                                            if ($item['attributes']) {
                                                $attrs = json_decode($item['attributes'], true);
                                                if ($attrs) {
                                                    echo '<div class="product-attributes">';
                                                    foreach($attrs as $key => $value) {
                                                        echo '<small>' . htmlspecialchars($key) . ': ' . htmlspecialchars($value) . '</small>';
                                                    }
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td><?php echo format_price($item['product_price']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td class="text-right"><?php echo format_price($item['total_price']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">Subtotal:</td>
                                    <td class="text-right"><?php echo format_price($order['subtotal']); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">Shipping:</td>
                                    <td class="text-right"><?php echo format_price($order['shipping_cost']); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">Tax:</td>
                                    <td class="text-right"><?php echo format_price($order['tax_amount']); ?></td>
                                </tr>
                                <tr class="total-row">
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong><?php echo format_price($order['total_amount']); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="info-grid">
                    <div class="section-card">
                        <h3>Shipping Address</h3>
                        <div class="address-info">
                            <p><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></p>
                            <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                            <p><?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?></p>
                            <p><?php echo htmlspecialchars($order['zip_code']); ?></p>
                            <p><?php echo htmlspecialchars($order['country']); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        </div>
                    </div>

                    <div class="section-card">
                        <h3>Payment Information</h3>
                        <div class="payment-info">
                            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                            <p><strong>Payment Status:</strong> 
                                <span class="payment-status status-<?php echo $order['payment_status']; ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </p>
                            <p><strong>Order Total:</strong> <?php echo format_price($order['total_amount']); ?></p>
                            <p><strong>Order Date:</strong> <?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Order Actions -->
                <?php if ($order['order_status'] == 'pending'): ?>
                <div class="section-card">
                    <h3>Order Actions</h3>
                    <div class="action-buttons">
                        <button onclick="cancelOrder(<?php echo $order['id']; ?>)" class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancel Order
                        </button>
                        <?php if ($order['payment_status'] == 'pending'): ?>
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </a>
                        <?php endif; ?>
                        <a href="orders.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                </div>
                <?php endif; ?>
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
    </script>
</body>
</html>