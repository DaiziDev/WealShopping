<?php
require_once '../includes/config.php';

// Get filter parameters
$category_slug = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// FIX: Set realistic price ranges for FCFA prices
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 200000; // Changed from 1000 to 200,000

$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Debug: Check what's happening
// echo "<pre>Min Price: $min_price, Max Price: $max_price</pre>";

// Build query
$sql = "SELECT p.*, pi.image_url 
        FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE p.is_active = 1 AND p.quantity > 0";
$params = [];

if (!empty($category_slug)) {
    $sql .= " AND p.category_id IN (SELECT id FROM categories WHERE slug = ?)";
    $params[] = $category_slug;
}

if (!empty($search)) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ? OR p.short_description LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

// FIX: Ensure price filtering works correctly
$sql .= " AND p.price >= ? AND p.price <= ?";
$params[] = $min_price;
$params[] = $max_price;

// Sorting
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'name':
        $sql .= " ORDER BY p.name ASC";
        break;
    default:
        $sql .= " ORDER BY p.created_at DESC";
}

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// FIX: Count total products with same filters
$count_sql = "SELECT COUNT(*) as total FROM products p WHERE p.is_active = 1 AND p.quantity > 0";
$count_params = [];

// Apply same filters to count query
if (!empty($category_slug)) {
    $count_sql .= " AND p.category_id IN (SELECT id FROM categories WHERE slug = ?)";
    $count_params[] = $category_slug;
}

if (!empty($search)) {
    $count_sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ? OR p.short_description LIKE ?)";
    $search_term = "%$search%";
    $count_params[] = $search_term;
    $count_params[] = $search_term;
    $count_params[] = $search_term;
    $count_params[] = $search_term;
}

$count_sql .= " AND p.price >= ? AND p.price <= ?";
$count_params[] = $min_price;
$count_params[] = $max_price;

$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($count_params);
$count_result = $count_stmt->fetch();
$total_products = $count_result['total'];

// Apply pagination to main query
$sql .= " LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for sidebar
$categories = getCategories();

require_once '../includes/header.php';
?>

<!-- The rest of your HTML remains the same until the form -->
<section class="shop-section section-padding">
    <div class="container">
        <div class="shop-header">
            <h1 class="page-title">
                <?php 
                if (!empty($category_slug)) {
                    echo ucfirst(str_replace('-', ' ', $category_slug));
                } elseif (!empty($search)) {
                    echo "Search Results: " . htmlspecialchars($search);
                } else {
                    echo "All Products";
                }
                ?>
            </h1>
            <p class="page-subtitle"><?php echo $total_products; ?> products found</p>
        </div>
        
        <div class="shop-content">
            <!-- Sidebar Filters -->
            <div class="shop-sidebar">
                <div class="filter-section">
                    <h3>Categories</h3>
                    <ul class="category-list">
                        <li><a href="shop.php" class="<?php echo empty($category_slug) ? 'active' : ''; ?>">All Products</a></li>
                        <?php foreach ($categories as $cat): ?>
                        <li><a href="shop.php?category=<?php echo $cat['slug']; ?>" class="<?php echo $category_slug == $cat['slug'] ? 'active' : ''; ?>">
                            <?php echo $cat['name']; ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="filter-section">
                    <h3>Price Range (FCFA)</h3>
                    <form method="GET" class="price-filter">
                        <div class="price-inputs">
                            <input type="number" name="min_price" placeholder="Min" 
                                   value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>" 
                                   min="0" step="1000">
                            <span>to</span>
                            <input type="number" name="max_price" placeholder="Max" 
                                   value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>" 
                                   min="0" step="1000">
                        </div>
                        <?php if (!empty($category_slug)): ?>
                        <input type="hidden" name="category" value="<?php echo $category_slug; ?>">
                        <?php endif; ?>
                        <?php if (!empty($search)): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <?php endif; ?>
                        <?php if (!empty($sort) && $sort != 'newest'): ?>
                        <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                        <?php endif; ?>
                        <button type="submit" class="btn btn-sm btn-outline">Apply</button>
                        <a href="shop.php<?php 
                            echo !empty($category_slug) ? '?category=' . $category_slug : '';
                            echo !empty($search) ? (empty($category_slug) ? '?search=' . urlencode($search) : '&search=' . urlencode($search)) : '';
                            echo !empty($sort) && $sort != 'newest' ? ((empty($category_slug) && empty($search)) ? '?sort=' . $sort : '&sort=' . $sort) : '';
                        ?>" class="btn btn-sm btn-link">Clear</a>
                    </form>
                </div>
                
                <!-- FIX: Add price range suggestions -->
                <div class="filter-section">
                    <h4>Quick Price Filters</h4>
                    <ul class="price-range-list">
                        <li><a href="shop.php?min_price=0&max_price=20000<?php 
                            echo !empty($category_slug) ? '&category=' . $category_slug : '';
                            echo !empty($search) ? '&search=' . urlencode($search) : '';
                        ?>">Under 20,000 FCFA</a></li>
                        <li><a href="shop.php?min_price=20000&max_price=50000<?php 
                            echo !empty($category_slug) ? '&category=' . $category_slug : '';
                            echo !empty($search) ? '&search=' . urlencode($search) : '';
                        ?>">20,000 - 50,000 FCFA</a></li>
                        <li><a href="shop.php?min_price=50000&max_price=100000<?php 
                            echo !empty($category_slug) ? '&category=' . $category_slug : '';
                            echo !empty($search) ? '&search=' . urlencode($search) : '';
                        ?>">50,000 - 100,000 FCFA</a></li>
                        <li><a href="shop.php?min_price=100000&max_price=200000<?php 
                            echo !empty($category_slug) ? '&category=' . $category_slug : '';
                            echo !empty($search) ? '&search=' . urlencode($search) : '';
                        ?>">100,000+ FCFA</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="shop-main">
                <div class="shop-toolbar">
                    <div class="sort-by">
                        <label>Sort by:</label>
                        <select id="sortSelect" onchange="window.location.href = this.value">
                            <option value="?sort=newest<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="?sort=price_low<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="?sort=price_high<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="?sort=name<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
                        </select>
                    </div>
                </div>
                
                <div class="products-grid">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): 
                            // Get image URL
                            $image_url = get_product_image_url($product['image_url']);
                        ?>
                        <div class="product-card">
                            <div class="product-image" style="background-image: url('<?php echo $image_url; ?>');">
                                <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                <span class="product-badge sale">Sale</span>
                                <?php elseif ($product['featured']): ?>
                                <span class="product-badge">Featured</span>
                                <?php endif; ?>
                                <button class="wishlist-btn" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>"><?php echo $product['name']; ?></a>
                                </h3>
                                <p class="product-category"><?php echo $product['brand'] ?: 'EliteStyle'; ?></p>
                                <div class="product-price">
                                    <span class="current-price"><?php echo format_price($product['price']); ?></span>
                                    <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                    <span class="original-price"><?php echo format_price($product['compare_price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-sm add-to-cart" 
                                        data-product-id="<?php echo $product['id']; ?>" 
                                        data-product-name="<?php echo htmlspecialchars($product['name']); ?>" 
                                        data-product-price="<?php echo $product['price']; ?>"
                                        data-image-url="<?php echo htmlspecialchars($image_url); ?>">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-products">
                            <i class="fas fa-search fa-3x"></i>
                            <h3>No products found</h3>
                            <p>Try adjusting your search or filter criteria</p>
                            <a href="shop.php" class="btn btn-primary">Browse All Products</a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_products > $per_page): ?>
                <div class="pagination">
                    <?php
                    $total_pages = ceil($total_products / $per_page);
                    $base_url = "shop.php?";
                    $base_url .= !empty($category_slug) ? "category=$category_slug&" : "";
                    $base_url .= !empty($search) ? "search=" . urlencode($search) . "&" : "";
                    $base_url .= "min_price=$min_price&max_price=$max_price&sort=$sort&";
                    
                    if ($page > 1): ?>
                    <a href="<?php echo $base_url; ?>page=<?php echo $page - 1; ?>" class="page-link prev"><i class="fas fa-chevron-left"></i></a>
                    <?php endif;
                    
                    for ($i = 1; $i <= $total_pages; $i++):
                        if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                        <a href="<?php echo $base_url; ?>page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                        <span class="page-dots">...</span>
                        <?php endif;
                    endfor;
                    
                    if ($page < $total_pages): ?>
                    <a href="<?php echo $base_url; ?>page=<?php echo $page + 1; ?>" class="page-link next"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Add to Cart JavaScript -->
<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = $(this).data('product-price');
        const imageUrl = $(this).data('image-url');
        const button = $(this);
        
        console.log('Adding to cart:', { productId, productName, productPrice, imageUrl });
        
        // Disable button temporarily to prevent double clicks
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Adding...');
        
        $.ajax({
            url: '../includes/add-to-cart.php',
            type: 'POST',
            data: {
                product_id: productId,
                product_name: productName,
                product_price: productPrice,
                image_url: imageUrl,
                quantity: 1
            },
            dataType: 'json',
            success: function(response) {
                console.log('Add to cart response:', response);
                
                if (response.success) {
                    // Update cart count
                    updateCartCount(response.cart_count);
                    
                    // Show success notification
                    showNotification(response.message, 'success');
                    
                    // Change button text temporarily
                    button.html('<i class="fas fa-check"></i> Added!');
                    setTimeout(function() {
                        button.html('Add to Cart').prop('disabled', false);
                    }, 1500);
                } else {
                    // Show error notification
                    showNotification(response.message, 'error');
                    button.html('Add to Cart').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error, xhr.responseText);
                showNotification('Network error. Please try again.', 'error');
                button.html('Add to Cart').prop('disabled', false);
            }
        });
    });
    
    // Wishlist functionality
    $('.wishlist-btn').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const button = $(this);
        
        // Toggle heart icon
        const icon = button.find('i');
        if (icon.hasClass('far')) {
            icon.removeClass('far').addClass('fas');
            showNotification('Added to wishlist', 'success');
            
            // AJAX call to add to wishlist
            $.post('../includes/wishlist.php', { 
                action: 'add', 
                product_id: productId 
            });
        } else {
            icon.removeClass('fas').addClass('far');
            showNotification('Removed from wishlist', 'info');
            
            // AJAX call to remove from wishlist
            $.post('../includes/wishlist.php', { 
                action: 'remove', 
                product_id: productId 
            });
        }
    });
});

function updateCartCount(count) {
    // Remove existing cart counts
    $('.cart-count').remove();
    
    if (count > 0) {
        // Add cart count to all cart buttons
        $('.cart-btn').each(function() {
            $(this).append('<span class="cart-count">' + count + '</span>');
        });
        
        // Also update mobile cart count
        $('.mobile-cart-count').text(count);
    }
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    $('.notification').remove();
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
    
    // Close button
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    });
}

// Add notification styles if not already added
if (!$('.notification-style').length) {
    const style = document.createElement('style');
    style.className = 'notification-style';
    style.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            max-width: 400px;
            transform: translateX(150%);
            transition: transform 0.3s ease;
            z-index: 10000;
            border-left: 4px solid;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }
        
        .notification-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .notification-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .notification-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }
        
        .notification-warning {
            background: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            margin-left: 15px;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            .notification {
                left: 20px;
                right: 20px;
                max-width: none;
                transform: translateY(-150%);
            }
            
            .notification.show {
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
}
</script>

<?php
require_once '../includes/footer.php';
?>