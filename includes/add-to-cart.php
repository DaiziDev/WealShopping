<?php
require_once '../includes/config.php';

// Get filter parameters
$category_slug = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 1000;
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

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
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$sql .= " AND p.price BETWEEN ? AND ?";
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

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$total_products = $stmt->rowCount();

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
                    <h3>Price Range</h3>
                    <form method="GET" class="price-filter">
                        <div class="price-inputs">
                            <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price; ?>" min="0">
                            <span>to</span>
                            <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price; ?>" min="0">
                        </div>
                        <?php if (!empty($category_slug)): ?>
                        <input type="hidden" name="category" value="<?php echo $category_slug; ?>">
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
                            <option value="?sort=newest<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="?sort=price_low<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="?sort=price_high<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="?sort=name<?php echo !empty($category_slug) ? '&category=' . $category_slug : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
                        </select>
                    </div>
                </div>
                
                <div class="products-grid">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image" style="background-image: url('<?php echo $product['image_url'] ?: '../assets/images/still-life-rendering-jackets-display.jpg'; ?>');">
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
                                    <span class="current-price">$<?php echo number_format($product['price'], 2); ?></span>
                                    <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                    <span class="original-price">$<?php echo number_format($product['compare_price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-sm add-to-cart" data-product-id="<?php echo $product['id']; ?>" data-product-name="<?php echo htmlspecialchars($product['name']); ?>" data-product-price="<?php echo $product['price']; ?>">
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

<?php
require_once '../includes/footer.php';
?>