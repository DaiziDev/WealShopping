<?php
// admin/customers.php
require_once 'auth_check.php';

// Handle actions
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// Delete customer
if ($action == 'delete' && $id) {
    // Check if customer has orders before deleting
    $sql = "SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    
    if ($result['order_count'] == 0) {
        // Delete customer
        $sql = "DELETE FROM users WHERE id = ? AND role = 'customer'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Customer deleted successfully!";
    } else {
        $_SESSION['error'] = "Cannot delete customer with existing orders!";
    }
    
    header('Location: customers.php');
    exit();
}

// Search and filter parameters
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 20; // Customers per page
$offset = ($page - 1) * $limit;

// Build WHERE clause for search
$whereClause = "WHERE role = 'customer'";
$params = [];
if ($search) {
    $whereClause .= " AND (username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
    $searchTerm = "%{$search}%";
    $params = array_fill(0, 4, $searchTerm);
}

// Get total customers count
$sql = "SELECT COUNT(*) as total FROM users $whereClause";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$total_customers = $stmt->fetch()['total'];
$total_pages = ceil($total_customers / $limit);

// Get customers with pagination
$sql = "SELECT * FROM users $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
$fullParams = array_merge($params, [$limit, $offset]);
$stmt->execute($fullParams);
$customers = $stmt->fetchAll();

// Get customer statistics
$stats_sql = "
    SELECT 
        (SELECT COUNT(*) FROM users WHERE role = 'customer') as total_customers,
        (SELECT COUNT(*) FROM users WHERE role = 'customer' AND DATE(created_at) = CURDATE()) as new_today,
        (SELECT COUNT(*) FROM users WHERE role = 'customer' AND MONTH(created_at) = MONTH(CURDATE())) as new_this_month,
        (SELECT COUNT(DISTINCT user_id) FROM orders WHERE user_id IS NOT NULL) as customers_with_orders
";
$stats_stmt = $pdo->query($stats_sql);
$customer_stats = $stats_stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers - WealShopping Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .customer-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .customer-details {
            display: flex;
            flex-direction: column;
        }
        
        .customer-name {
            font-weight: 600;
            color: #333;
        }
        
        .customer-email {
            font-size: 12px;
            color: #666;
        }
        
        .customer-actions {
            display: flex;
            gap: 5px;
        }
        
        .stats-mini-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-mini-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .stat-mini-value {
            font-size: 24px;
            font-weight: 700;
            color: #4361ee;
            margin: 5px 0;
        }
        
        .stat-mini-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .search-box {
            max-width: 400px;
            margin-bottom: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-form input {
            flex: 1;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #4361ee;
        }
        
        .pagination a:hover {
            background: #f8f9fa;
        }
        
        .pagination .active {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
        }
        
        .pagination .disabled {
            color: #999;
            cursor: not-allowed;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-header h3 {
            margin: 0;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        
        .modal-body {
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            color: #333;
        }
        
        .order-history {
            margin-top: 20px;
        }
        
        .order-history h4 {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .order-id {
            font-weight: 500;
            color: #4361ee;
        }
        
        .order-date {
            font-size: 12px;
            color: #666;
        }
        
        .order-amount {
            font-weight: 600;
        }
        
        .order-status {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .status-shipped {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="admin-main">
            <!-- Customers Header -->
            <header class="admin-header">
                <div>
                    <h1>Customers</h1>
                    <small>Manage your customers and their information</small>
                </div>
                <div>
                    <button class="btn btn-secondary" onclick="exportCustomers()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </header>

            <!-- Display success/error messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Customer Statistics -->
            <div class="stats-mini-cards">
                <div class="stat-mini-card">
                    <i class="fas fa-users" style="color: #4361ee;"></i>
                    <div class="stat-mini-value"><?php echo $customer_stats['total_customers']; ?></div>
                    <div class="stat-mini-label">Total Customers</div>
                </div>
                
                <div class="stat-mini-card">
                    <i class="fas fa-user-plus" style="color: #4CAF50;"></i>
                    <div class="stat-mini-value"><?php echo $customer_stats['new_today']; ?></div>
                    <div class="stat-mini-label">New Today</div>
                </div>
                
                <div class="stat-mini-card">
                    <i class="fas fa-calendar-alt" style="color: #FF9800;"></i>
                    <div class="stat-mini-value"><?php echo $customer_stats['new_this_month']; ?></div>
                    <div class="stat-mini-label">This Month</div>
                </div>
                
                <div class="stat-mini-card">
                    <i class="fas fa-shopping-cart" style="color: #9C27B0;"></i>
                    <div class="stat-mini-value"><?php echo $customer_stats['customers_with_orders']; ?></div>
                    <div class="stat-mini-label">With Orders</div>
                </div>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <form method="GET" action="" class="search-form">
                    <input type="text" name="search" placeholder="Search customers by name, email, or username..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if ($search): ?>
                        <a href="customers.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Customers Table -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Customer List</h2>
                    <div>
                        <small>Showing <?php echo count($customers); ?> of <?php echo $total_customers; ?> customers</small>
                    </div>
                </div>
                
                <div class="table-container">
                    <?php if (count($customers) > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                    <th>Orders</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($customers as $customer): ?>
                                <?php
                                // Get order count for this customer
                                $order_sql = "SELECT COUNT(*) as order_count, SUM(total_amount) as total_spent FROM orders WHERE user_id = ?";
                                $order_stmt = $pdo->prepare($order_sql);
                                $order_stmt->execute([$customer['id']]);
                                $order_info = $order_stmt->fetch();
                                
                                // Get initials for avatar
                                $initials = '';
                                if ($customer['first_name'] && $customer['last_name']) {
                                    $initials = substr($customer['first_name'], 0, 1) . substr($customer['last_name'], 0, 1);
                                } elseif ($customer['username']) {
                                    $initials = substr($customer['username'], 0, 2);
                                } else {
                                    $initials = substr($customer['email'], 0, 2);
                                }
                                ?>
                                <tr>
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                <?php echo strtoupper($initials); ?>
                                            </div>
                                            <div class="customer-details">
                                                <div class="customer-name">
                                                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                                </div>
                                                <div class="customer-email">
                                                    @<?php echo htmlspecialchars($customer['username']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($customer['email']); ?></div>
                                        <?php if ($customer['phone']): ?>
                                        <small style="color: #666;"><?php echo htmlspecialchars($customer['phone']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($customer['city']): ?>
                                        <div><?php echo htmlspecialchars($customer['city']); ?></div>
                                        <?php endif; ?>
                                        <?php if ($customer['country']): ?>
                                        <small style="color: #666;"><?php echo htmlspecialchars($customer['country']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;"><?php echo $order_info['order_count']; ?> orders</div>
                                        <?php if ($order_info['total_spent']): ?>
                                        <small style="color: #4CAF50;">$<?php echo number_format($order_info['total_spent'], 2); ?> spent</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></div>
                                        <small style="color: #666;"><?php echo date('H:i', strtotime($customer['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="customer-actions">
                                            <button class="btn btn-primary btn-sm" onclick="viewCustomer(<?php echo $customer['id']; ?>)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-secondary btn-sm" onclick="editCustomer(<?php echo $customer['id']; ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="confirmDelete(<?php echo $customer['id']; ?>, '<?php echo htmlspecialchars(addslashes($customer['first_name'] . ' ' . $customer['last_name'])); ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=1<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                                <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php
                            $start = max(1, $page - 2);
                            $end = min($total_pages, $page + 2);
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"
                                   class="<?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                                <a href="?page=<?php echo $total_pages; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-users-slash"></i>
                            <h3>No customers found</h3>
                            <p><?php echo $search ? 'Try a different search term' : 'No customers have registered yet'; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Customer Detail Modal -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Customer Details</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="customerModalBody">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <script>
        // Function to view customer details
        function viewCustomer(customerId) {
            fetch(`ajax-handlers.php?action=get_customer&id=${customerId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const customer = data.customer;
                        const orders = data.orders || [];
                        
                        let modalContent = `
                            <div class="customer-info">
                                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                                    <div class="customer-avatar" style="width: 60px; height: 60px; font-size: 24px;">
                                        ${customer.initials}
                                    </div>
                                    <div>
                                        <h3 style="margin: 0 0 5px 0;">${customer.full_name}</h3>
                                        <p style="margin: 0; color: #666;">${customer.email}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Username</span>
                                    <span class="info-value">${customer.username}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Phone</span>
                                    <span class="info-value">${customer.phone || 'Not provided'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Location</span>
                                    <span class="info-value">${customer.city ? customer.city + ', ' : ''}${customer.country || 'Not provided'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Joined</span>
                                    <span class="info-value">${customer.join_date}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Total Orders</span>
                                    <span class="info-value">${customer.order_count}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Total Spent</span>
                                    <span class="info-value">$${customer.total_spent}</span>
                                </div>
                            </div>
                            
                            ${customer.address ? `
                            <div class="info-item" style="margin-bottom: 20px;">
                                <span class="info-label">Address</span>
                                <span class="info-value" style="white-space: pre-line;">${customer.address}</span>
                            </div>
                            ` : ''}
                        `;
                        
                        if (orders.length > 0) {
                            modalContent += `
                                <div class="order-history">
                                    <h4>Recent Orders</h4>
                                    ${orders.map(order => `
                                        <div class="order-item">
                                            <div>
                                                <div class="order-id">Order #${order.order_number}</div>
                                                <div class="order-date">${order.date}</div>
                                            </div>
                                            <div>
                                                <div class="order-amount">$${order.total}</div>
                                                <span class="order-status status-${order.status.toLowerCase()}">${order.status}</span>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        }
                        
                        document.getElementById('customerModalBody').innerHTML = modalContent;
                        document.getElementById('customerModal').style.display = 'flex';
                    } else {
                        alert(data.message || 'Failed to load customer details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load customer details');
                });
        }
        
        // Function to edit customer
        function editCustomer(customerId) {
            // Redirect to edit page (to be implemented)
            // For now, show alert
            alert('Edit functionality will be implemented in the next version.');
            // window.location.href = `edit-customer.php?id=${customerId}`;
        }
        
        // Function to confirm deletion
        function confirmDelete(customerId, customerName) {
            if (confirm(`Are you sure you want to delete customer "${customerName}"?\n\nThis action cannot be undone.`)) {
                window.location.href = `customers.php?action=delete&id=${customerId}`;
            }
        }
        
        // Function to close modal
        function closeModal() {
            document.getElementById('customerModal').style.display = 'none';
        }
        
        // Function to export customers
        function exportCustomers() {
            const search = new URLSearchParams(window.location.search).get('search') || '';
            window.location.href = `ajax-handlers.php?action=export_customers&search=${encodeURIComponent(search)}`;
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('customerModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        
        // Add active class to customers link in sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'customers.php') {
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