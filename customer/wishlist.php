<?php
// customer/wishlist.php
require_once 'auth_check.php';

// Handle wishlist actions
$action = $_GET['action'] ?? '';
$item_id = $_GET['id'] ?? 0;

if ($action == 'remove' && $item_id) {
    // Remove item from wishlist
    $sql = "DELETE FROM wishlist WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_id, $current_user['id']]);
    
    $_SESSION['success'] = "Item removed from wishlist!";
    header('Location: wishlist.php');
    exit();
}

if ($action == 'clear') {
    // Clear all items from wishlist
    $sql = "DELETE FROM wishlist WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$current_user['id']]);
    
    $_SESSION['success'] = "Wishlist cleared!";
    header('Location: wishlist.php');
    exit();
}

// Get wishlist items
$sql = "SELECT w.*, p.name, p.price, p.slug, p.quantity as stock, pi.image_url 
        FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE w.user_id = ? AND p.is_active = 1
        ORDER BY w.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_user['id']]);
$wishlist_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - <?php echo SITE_NAME; ?></title>
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
                <!-- Wishlist Header -->
                <div class="customer-header">
                    <h1>My Wishlist</h1>
                    <p>Save items you love for later</p>
                </div>

                <!-- Display messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Wishlist Actions -->
                <div class="wishlist-actions">
                    <div class="wishlist-count">
                        <?php echo count($wishlist_items); ?> item(s) in wishlist
                    </div>
                    <div class="action-buttons">
                        <?php if (count($wishlist_items) > 0): ?>
                        <a href="?action=clear" class="btn btn-danger btn-sm" onclick="return confirm('Clear all items from wishlist?')">
                            <i class="fas fa-trash"></i> Clear All
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Wishlist Items -->
                <div class="section-card">
                    <?php if (count($wishlist_items) > 0): ?>
                        <div class="products-grid wishlist-grid">
                            <?php foreach($wishlist_items as $item): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="<?php echo site_url('pages/product-detail.php?slug=' . $item['slug']); ?>">
                                        <img src="<?php echo get_product_image_url($item['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </a>
                                    <button class="remove-wishlist" 
                                            onclick="if(confirm('Remove from wishlist?')) window.location.href='?action=remove&id=<?php echo $item['id']; ?>'">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="product-info">
                                    <h4 class="product-title">
                                        <a href="<?php echo site_url('pages/product-detail.php?slug=' . $item['slug']); ?>">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    </h4>
                                    <div class="product-price"><?php echo format_price($item['price']); ?></div>
                                    <div class="product-stock">
                                        <?php if ($item['stock'] > 0): ?>
                                            <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
                                        <?php else: ?>
                                            <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-actions">
                                        <?php if ($item['stock'] > 0): ?>
                                        <a href="../includes/add-to-cart.php?product_id=<?php echo $item['product_id']; ?>&return=wishlist" 
                                           class="btn btn-primary">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </a>
                                        <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-bell"></i> Notify When Available
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-heart"></i>
                            <h3>Your wishlist is empty</h3>
                            <p>Save items you love by clicking the heart icon on any product.</p>
                            <a href="<?php echo site_url('pages/shop.php'); ?>" class="btn btn-primary">Browse Products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>