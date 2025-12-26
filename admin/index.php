<?php
require_once '../includes/config.php';

// Check if user is admin
if (!isAdmin()) {
    header('Location: ../pages/login.php');
    exit();
}

// Get stats for dashboard
$sql = "SELECT COUNT(*) as total FROM products";
$stmt = $pdo->query($sql);
$total_products = $stmt->fetch()['total'];

$sql = "SELECT COUNT(*) as total FROM orders";
$stmt = $pdo->query($sql);
$total_orders = $stmt->fetch()['total'];

$sql = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$stmt = $pdo->query($sql);
$total_customers = $stmt->fetch()['total'];

$sql = "SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'";
$stmt = $pdo->query($sql);
$total_revenue = $stmt->fetch()['total'] ?? 0;

// Recent orders
$sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
$recent_orders = $pdo->query($sql)->fetchAll();

// Low stock products
$sql = "SELECT * FROM products WHERE quantity <= low_stock_threshold ORDER BY quantity ASC LIMIT 5";
$low_stock = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px 0;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
        }
        
        .sidebar-header h2 {
            color: white;
            font-size: 1.5rem;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #34495e;
            color: white;
        }
        
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .card-1 .icon { background: #e3f2fd; color: #1976d2; }
        .card-2 .icon { background: #f3e5f5; color: #7b1fa2; }
        .card-3 .icon { background: #e8f5e9; color: #388e3c; }
        .card-4 .icon { background: #fff3e0; color: #f57c00; }
        
        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .section h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }
        
        table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status.pending { background: #fff3cd; color: #856404; }
        .status.processing { background: #d1ecf1; color: #0c5460; }
        .status.shipped { background: #d4edda; color: #155724; }
        .status.delivered { background: #c3e6cb; color: #155724; }
        .status.paid { background: #d4edda; color: #155724; }
        
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge.warning { background: #fff3cd; color: #856404; }
        .badge.danger { background: #f8d7da; color: #721c24; }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-crown"></i> <?php echo SITE_NAME; ?> Admin</h2>
            <p>Welcome, <?php echo $_SESSION['first_name']; ?>!</p>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="products.php"><i class="fas fa-tshirt"></i> Products</a></li>
            <li><a href="categories.php"><i class="fas fa-list"></i> Categories</a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
            <li><a href="coupons.php"><i class="fas fa-tag"></i> Coupons</a></li>
            <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
        
        <div style="padding: 20px;">
            <a href="../index.php" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                <i class="fas fa-store"></i> View Store
            </a>
            <a href="../includes/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Dashboard Overview</h1>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card card-1">
                <div class="icon"><i class="fas fa-tshirt"></i></div>
                <h3>Total Products</h3>
                <div class="value"><?php echo $total_products; ?></div>
            </div>
            
            <div class="stat-card card-2">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <h3>Total Orders</h3>
                <div class="value"><?php echo $total_orders; ?></div>
            </div>
            
            <div class="stat-card card-3">
                <div class="icon"><i class="fas fa-users"></i></div>
                <h3>Total Customers</h3>
                <div class="value"><?php echo $total_customers; ?></div>
            </div>
            
            <div class="stat-card card-4">
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <h3>Total Revenue</h3>
                <div class="value">$<?php echo number_format($total_revenue, 2); ?></div>
            </div>
        </div>
        
        <div class="row" style="display: flex; gap: 20px;">
            <!-- Recent Orders -->
            <div class="section" style="flex: 2;">
                <h2>Recent Orders</h2>
                <table>
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
                        <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_number']; ?></td>
                            <td><?php echo $order['customer_name']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="status <?php echo $order['order_status']; ?>">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Low Stock Alert -->
            <div class="section" style="flex: 1;">
                <h2>Low Stock Alert</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock as $product): ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td>
                                <?php if ($product['quantity'] == 0): ?>
                                <span class="badge danger">Out of Stock</span>
                                <?php else: ?>
                                <span class="badge warning">Low Stock</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>