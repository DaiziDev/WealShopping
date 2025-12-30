<?php
// customer/sidebar.php
// This file is included in customer panel pages
?>
<aside class="customer-sidebar">
    <div class="customer-profile-summary">
        <div class="profile-avatar">
            <?php
            // Get user initials for avatar
            $initials = '';
            if ($current_user['first_name'] && $current_user['last_name']) {
                $initials = substr($current_user['first_name'], 0, 1) . substr($current_user['last_name'], 0, 1);
            } elseif ($current_user['username']) {
                $initials = substr($current_user['username'], 0, 2);
            } else {
                $initials = substr($current_user['email'], 0, 2);
            }
            ?>
            <div class="avatar-circle">
                <?php echo strtoupper($initials); ?>
            </div>
            <div class="profile-info">
                <h4><?php echo htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']); ?></h4>
                <p><?php echo htmlspecialchars($current_user['email']); ?></p>
            </div>
        </div>
    </div>

    <nav class="customer-nav">
        <ul>
            <li>
                <a href="dashboard.php" class="<?php echo isActivePage('dashboard.php'); ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="orders.php" class="<?php echo isActivePage('orders.php'); ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span>My Orders</span>
                </a>
            </li>
            <li>
                <a href="wishlist.php" class="<?php echo isActivePage('wishlist.php'); ?>">
                    <i class="fas fa-heart"></i>
                    <span>Wishlist</span>
                </a>
            </li>
            <li>
                <a href="profile.php" class="<?php echo isActivePage('profile.php'); ?>">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>