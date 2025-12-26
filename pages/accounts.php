<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';

// Get user orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<section class="account-section section-padding">
    <div class="container">
        <h1 class="page-title">My Account</h1>
        
        <div class="account-content">
            <!-- Sidebar -->
            <div class="account-sidebar">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></h3>
                    <p><?php echo $_SESSION['email']; ?></p>
                </div>
                
                <ul class="account-menu">
                    <li>
                        <a href="?tab=profile" class="<?php echo $tab == 'profile' ? 'active' : ''; ?>">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="?tab=orders" class="<?php echo $tab == 'orders' ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-bag"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="?tab=addresses" class="<?php echo $tab == 'addresses' ? 'active' : ''; ?>">
                            <i class="fas fa-map-marker-alt"></i> Addresses
                        </a>
                    </li>
                    <li>
                        <a href="?tab=wishlist" class="<?php echo $tab == 'wishlist' ? 'active' : ''; ?>">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="../includes/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="account-main">
                <?php if ($tab == 'profile'): ?>
                <!-- Profile Tab -->
                <div class="tab-content active">
                    <h2>Profile Information</h2>
                    <form class="profile-form">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" value="<?php echo $_SESSION['first_name']; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" value="<?php echo $_SESSION['last_name']; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" value="<?php echo $_SESSION['email']; ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" class="form-control" value="<?php echo $user['phone']; ?>" disabled>
                        </div>
                        
                        <button type="button" class="btn btn-primary" onclick="enableEdit()">Edit Profile</button>
                    </form>
                </div>
                
                <?php elseif ($tab == 'orders'): ?>
                <!-- Orders Tab -->
                <div class="tab-content active">
                    <h2>My Orders</h2>
                    
                    <?php if (count($orders) > 0): ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <h4>Order #<?php echo $order['order_number']; ?></h4>
                                    <p class="order-date"><?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
                                </div>
                                <div class="order-status">
                                    <span class="status <?php echo $order['order_status']; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-body">
                                <div class="order-total">
                                    <strong>Total: $<?php echo number_format($order['total_amount'], 2); ?></strong>
                                </div>
                                <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag fa-3x"></i>
                        <h3>No orders yet</h3>
                        <p>You haven't placed any orders yet.</p>
                        <a href="shop.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php elseif ($tab == 'wishlist'): ?>
                <!-- Wishlist Tab -->
                <div class="tab-content active">
                    <h2>My Wishlist</h2>
                    <div class="wishlist-grid">
                        <!-- Wishlist items will be loaded here -->
                        <p class="empty-state">Your wishlist is empty</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>