<?php
// admin/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-crown"></i> WealShopping</h2>
        <p>Admin Panel</p>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="products.php" class="<?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tshirt"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="categories.php" class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="orders.php" class="<?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="customers.php" class="<?php echo $current_page == 'customers.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="coupons.php" class="<?php echo $current_page == 'coupons.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tag"></i>
                    <span>Coupons</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                <i class="fas fa-user-circle" style="color: white; font-size: 1.5rem;"></i>
            </div>
            <div>
                <strong style="display: block; font-size: 0.9rem;">Admin User</strong>
                <small style="color: rgba(255,255,255,0.6); font-size: 0.8rem;">admin@wealshopping.com</small>
            </div>
        </div>
        <a href="../includes/logout.php" class="logout-btn" style="display: flex; align-items: center; color: rgba(255,255,255,0.7); text-decoration: none; padding: 8px 0;">
            <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>