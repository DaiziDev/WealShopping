<?php
require_once '../includes/config.php';

// Get filter parameters
$category_slug = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 500000;
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$per_page = 12;
$offset = ($page - 1) * $per_page;

// Get products
$products = getProducts($category_slug, $search, $min_price, $max_price, $sort, $per_page, $offset);

// Get total count for pagination
$total_products = countProducts($category_slug, $search, $min_price, $max_price);

// Get categories for sidebar
$categories = getCategories();

// Get category name if filtering by category
$category_name = '';
if (!empty($category_slug)) {
    $sql = "SELECT name FROM categories WHERE slug = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_slug]);
    $category = $stmt->fetch();
    if ($category) {
        $category_name = $category['name'];
    }
}

require_once '../includes/header.php';
?>

<section class="shop-section section-padding">
    <div class="container">
        <div class="shop-header">
            <h1 class="page-title">
                <?php 
                if (!empty($category_name)) {
                    echo htmlspecialchars($category_name);
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
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="filter-section">
                    <h3>Price Range</h3>
                    <form method="GET" class="price-filter">
                        <div class="price-inputs">
                            <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price; ?>" min="0" step="1000">
                            <span>to</span>
                            <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price; ?>" min="0" step="1000">
                        </div>
                        <?php if (!empty($category_slug)): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_slug); ?>">
                        <?php endif; ?>
                        <?php if (!empty($search)): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <?php endif; ?>
                        <button type="submit" class="btn btn-sm btn-outline">Apply</button>
                    </form>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="shop-main">
                <div class="shop-toolbar">
                    <div class="sort-by">
                        <label>Sort by:</label>
                        <select id="sortSelect" onchange="window.location.href = this.value">
                            <?php
                            $base_url = "shop.php?";
                            $base_url .= !empty($category_slug) ? "category=" . urlencode($category_slug) . "&" : "";
                            $base_url .= !empty($search) ? "search=" . urlencode($search) . "&" : "";
                            $base_url .= "min_price=$min_price&max_price=$max_price&";
                            ?>
                            <option value="<?php echo $base_url; ?>sort=newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="<?php echo $base_url; ?>sort=price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="<?php echo $base_url; ?>sort=price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="<?php echo $base_url; ?>sort=name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
                        </select>
                    </div>
                </div>
                
                <div class="products-grid">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): 
                            // Get image URL using the fixed function
                            $image_url = get_product_image_url($product['image_url']);
                        ?>
                        <div class="product-card">
                            <div class="product-image" style="background-image: url('<?php echo htmlspecialchars($image_url); ?>');">
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
                                    <a href="product-detail.php?slug=<?php echo $product['slug']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                </h3>
                                <p class="product-category"><?php echo htmlspecialchars($product['brand'] ?: 'EliteStyle'); ?></p>
                                <div class="product-price">
                                    <span class="product-price"><?php echo format_price($product['price']); ?></span>
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
                <?php if ($total_products > $per_page): 
                $total_pages = ceil($total_products / $per_page);
                ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                    <a href="?<?php 
                        echo (!empty($category_slug) ? "category=" . urlencode($category_slug) . "&" : "");
                        echo (!empty($search) ? "search=" . urlencode($search) . "&" : "");
                        echo "min_price=$min_price&max_price=$max_price&sort=$sort&page=" . ($page - 1); 
                    ?>" class="page-link prev"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>
                    
                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    
                    if ($start_page > 1) {
                        echo '<a href="?';
                        echo (!empty($category_slug) ? "category=" . urlencode($category_slug) . "&" : "");
                        echo (!empty($search) ? "search=" . urlencode($search) . "&" : "");
                        echo "min_price=$min_price&max_price=$max_price&sort=$sort&page=1";
                        echo '" class="page-link">1</a>';
                        if ($start_page > 2) echo '<span class="page-dots">...</span>';
                    }
                    
                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a href="?<?php 
                        echo (!empty($category_slug) ? "category=" . urlencode($category_slug) . "&" : "");
                        echo (!empty($search) ? "search=" . urlencode($search) . "&" : "");
                        echo "min_price=$min_price&max_price=$max_price&sort=$sort&page=$i"; 
                    ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; 
                    
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) echo '<span class="page-dots">...</span>';
                        echo '<a href="?';
                        echo (!empty($category_slug) ? "category=" . urlencode($category_slug) . "&" : "");
                        echo (!empty($search) ? "search=" . urlencode($search) . "&" : "");
                        echo "min_price=$min_price&max_price=$max_price&sort=$sort&page=$total_pages";
                        echo '" class="page-link">' . $total_pages . '</a>';
                    }
                    ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <a href="?<?php 
                        echo (!empty($category_slug) ? "category=" . urlencode($category_slug) . "&" : "");
                        echo (!empty($search) ? "search=" . urlencode($search) . "&" : "");
                        echo "min_price=$min_price&max_price=$max_price&sort=$sort&page=" . ($page + 1); 
                    ?>" class="page-link next"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.add-to-cart').click(function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = $(this).data('product-price');
        const imageUrl = $(this).data('image-url');
        
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
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Update cart count
                        $('.cart-count').text(data.cart_count);
                        if (data.cart_count > 0) {
                            $('.cart-btn').append('<span class="cart-count">' + data.cart_count + '</span>');
                        }
                        
                        // Show notification
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message, 'error');
                    }
                } catch (e) {
                    showNotification('Error processing response', 'error');
                }
            },
            error: function() {
                showNotification('Network error. Please try again.', 'error');
            }
        });
    });
});

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
    
    // Close button
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    });
}

// Add notification styles
const style = document.createElement('style');
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
        z-index: 9999;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }
    
    .notification-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    
    .notification-info {
        background: #d1ecf1;
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        margin-left: 15px;
    }
`;
document.head.appendChild(style);
</script>

<?php
require_once '../includes/footer.php';
?>