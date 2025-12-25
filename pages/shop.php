<?php
$pageTitle = "Shop";
include_once '../includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li>Shop</li>
        </ul>
    </div>
</div>

<!-- Shop Section -->
<section class="shop section-padding">
    <div class="container">
        <div class="shop-header">
            <h1 class="page-title">Our Collection</h1>
            <p class="page-subtitle">Discover premium fashion items for every occasion</p>
        </div>
        
        <div class="shop-content">
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar">
                <div class="filter-section">
                    <h3>Categories</h3>
                    <ul class="filter-list">
                        <li><a href="?category=all" class="<?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'active' : ''; ?>">All Products</a></li>
                        <li><a href="?category=men" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'men') ? 'active' : ''; ?>">Men's Clothing</a></li>
                        <li><a href="?category=women" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'women') ? 'active' : ''; ?>">Women's Clothing</a></li>
                        <li><a href="?category=shoes" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'shoes') ? 'active' : ''; ?>">Footwear</a></li>
                        <li><a href="?category=accessories" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'accessories') ? 'active' : ''; ?>">Accessories</a></li>
                    </ul>
                </div>
                
                <div class="filter-section">
                    <h3>Price Range</h3>
                    <div class="price-range">
                        <input type="range" min="0" max="500" value="250" class="range-slider" id="priceRange">
                        <div class="price-values">
                            <span>$<span id="minPrice">0</span></span>
                            <span>$<span id="maxPrice">500</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Size</h3>
                    <div class="size-filters">
                        <button class="size-btn">XS</button>
                        <button class="size-btn">S</button>
                        <button class="size-btn active">M</button>
                        <button class="size-btn">L</button>
                        <button class="size-btn">XL</button>
                        <button class="size-btn">XXL</button>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Color</h3>
                    <div class="color-filters">
                        <button class="color-btn" style="background-color: #1a1a1a;" title="Black"></button>
                        <button class="color-btn" style="background-color: #6c757d;" title="Gray"></button>
                        <button class="color-btn" style="background-color: #ff4d6d;" title="Pink"></button>
                        <button class="color-btn" style="background-color: #5a67d8;" title="Blue"></button>
                        <button class="color-btn" style="background-color: #ffffff; border: 1px solid #ddd;" title="White"></button>
                        <button class="color-btn" style="background-color: #e9ecef;" title="Beige"></button>
                    </div>
                </div>
                
                <button class="btn btn-primary filter-apply">Apply Filters</button>
                <button class="btn btn-outline filter-reset">Reset All</button>
            </aside>
            
            <!-- Main Products Area -->
            <main class="shop-main">
                <div class="shop-toolbar">
                    <div class="toolbar-left">
                        <p>Showing <span id="productCount">12</span> of <span id="totalProducts">48</span> products</p>
                    </div>
                    <div class="toolbar-right">
                        <div class="sort-by">
                            <label for="sortSelect">Sort by:</label>
                            <select id="sortSelect">
                                <option value="featured">Featured</option>
                                <option value="newest">Newest</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="name">Name A-Z</option>
                            </select>
                        </div>
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid"><i class="fas fa-th-large"></i></button>
                            <button class="view-btn" data-view="list"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="products-grid shop-grid" id="productsGrid">
                    <!-- Product cards will be loaded here -->
                    <?php
                    // Sample products array - in real app, this would come from database
                    $products = [
                        [
                            'id' => 1,
                            'name' => 'Premium Denim Jacket',
                            'category' => 'Men\'s Clothing',
                            'price' => 89.99,
                            'original_price' => 120.00,
                            'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'New',
                            'sizes' => ['S', 'M', 'L', 'XL'],
                            'colors' => ['Blue', 'Black'],
                            'description' => 'High-quality denim jacket with modern fit'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Elegant Evening Dress',
                            'category' => 'Women\'s Clothing',
                            'price' => 149.99,
                            'original_price' => 199.99,
                            'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sale',
                            'sizes' => ['XS', 'S', 'M'],
                            'colors' => ['Red', 'Black'],
                            'description' => 'Elegant evening dress for special occasions'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Leather Running Shoes',
                            'category' => 'Footwear',
                            'price' => 129.99,
                            'original_price' => null,
                            'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => null,
                            'sizes' => ['8', '9', '10', '11'],
                            'colors' => ['White', 'Black'],
                            'description' => 'Comfortable running shoes with leather finish'
                        ],
                        [
                            'id' => 4,
                            'name' => 'Designer Handbag',
                            'category' => 'Accessories',
                            'price' => 199.99,
                            'original_price' => null,
                            'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Bestseller',
                            'sizes' => ['One Size'],
                            'colors' => ['Brown', 'Black'],
                            'description' => 'Luxury designer handbag with premium finish'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Casual T-Shirt',
                            'category' => 'Men\'s Clothing',
                            'price' => 29.99,
                            'original_price' => 39.99,
                            'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sale',
                            'sizes' => ['S', 'M', 'L', 'XL'],
                            'colors' => ['White', 'Gray', 'Black'],
                            'description' => 'Comfortable cotton t-shirt for everyday wear'
                        ],
                        [
                            'id' => 6,
                            'name' => 'Summer Dress',
                            'category' => 'Women\'s Clothing',
                            'price' => 59.99,
                            'original_price' => 79.99,
                            'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'New',
                            'sizes' => ['XS', 'S', 'M', 'L'],
                            'colors' => ['Yellow', 'White', 'Blue'],
                            'description' => 'Lightweight summer dress with floral pattern'
                        ],
                        [
                            'id' => 7,
                            'name' => 'Formal Dress Shoes',
                            'category' => 'Footwear',
                            'price' => 159.99,
                            'original_price' => null,
                            'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => null,
                            'sizes' => ['8', '9', '10', '11'],
                            'colors' => ['Black', 'Brown'],
                            'description' => 'Premium leather formal shoes for business occasions'
                        ],
                        [
                            'id' => 8,
                            'name' => 'Sunglasses',
                            'category' => 'Accessories',
                            'price' => 79.99,
                            'original_price' => 99.99,
                            'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sale',
                            'sizes' => ['One Size'],
                            'colors' => ['Black', 'Brown', 'Silver'],
                            'description' => 'UV protection sunglasses with stylish design'
                        ],
                        [
                            'id' => 9,
                            'name' => 'Winter Coat',
                            'category' => 'Men\'s Clothing',
                            'price' => 189.99,
                            'original_price' => 249.99,
                            'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sale',
                            'sizes' => ['M', 'L', 'XL', 'XXL'],
                            'colors' => ['Black', 'Gray', 'Navy'],
                            'description' => 'Warm winter coat with waterproof exterior'
                        ],
                        [
                            'id' => 10,
                            'name' => 'Business Suit',
                            'category' => 'Women\'s Clothing',
                            'price' => 299.99,
                            'original_price' => null,
                            'image' => 'https://images.unsplash.com/photo-1594938298603-8149c8d2d042?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'New',
                            'sizes' => ['XS', 'S', 'M'],
                            'colors' => ['Black', 'Navy', 'Gray'],
                            'description' => 'Professional business suit for corporate settings'
                        ],
                        [
                            'id' => 11,
                            'name' => 'Casual Sneakers',
                            'category' => 'Footwear',
                            'price' => 89.99,
                            'original_price' => 119.99,
                            'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sale',
                            'sizes' => ['7', '8', '9', '10', '11'],
                            'colors' => ['White', 'Black', 'Blue'],
                            'description' => 'Comfortable casual sneakers for everyday use'
                        ],
                        [
                            'id' => 12,
                            'name' => 'Leather Wallet',
                            'category' => 'Accessories',
                            'price' => 49.99,
                            'original_price' => null,
                            'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                            'badge' => null,
                            'sizes' => ['One Size'],
                            'colors' => ['Brown', 'Black'],
                            'description' => 'Genuine leather wallet with multiple compartments'
                        ],
                    ];
                    
                    foreach ($products as $product) {
                        $badgeClass = '';
                        if ($product['badge'] == 'Sale') {
                            $badgeClass = 'sale';
                        } elseif ($product['badge'] == 'Bestseller') {
                            $badgeClass = 'bestseller';
                        }
                        
                        echo '
                        <div class="product-card" data-id="' . $product['id'] . '" data-category="' . strtolower(explode(' ', $product['category'])[0]) . '">
                            <div class="product-image">
                                <img src="' . $product['image'] . '" alt="' . $product['name'] . '" loading="lazy">
                                ' . ($product['badge'] ? '<span class="product-badge ' . $badgeClass . '">' . $product['badge'] . '</span>' : '') . '
                                <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                                <button class="quick-view-btn" data-id="' . $product['id'] . '">Quick View</button>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><a href="product-detail.php?id=' . $product['id'] . '">' . $product['name'] . '</a></h3>
                                <p class="product-category">' . $product['category'] . '</p>
                                <div class="product-price">
                                    <span class="current-price">$' . number_format($product['price'], 2) . '</span>
                                    ' . ($product['original_price'] ? '<span class="original-price">$' . number_format($product['original_price'], 2) . '</span>' : '') . '
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-sm add-to-cart" data-id="' . $product['id'] . '">Add to Cart</button>
                                    <a href="product-detail.php?id=' . $product['id'] . '" class="btn btn-outline btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <button class="pagination-btn prev" disabled><i class="fas fa-chevron-left"></i> Previous</button>
                    <div class="pagination-numbers">
                        <button class="pagination-number active">1</button>
                        <button class="pagination-number">2</button>
                        <button class="pagination-number">3</button>
                        <button class="pagination-number">4</button>
                    </div>
                    <button class="pagination-btn next">Next <i class="fas fa-chevron-right"></i></button>
                </div>
            </main>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal" id="quickViewModal">
    <div class="modal-content">
        <button class="modal-close">&times;</button>
        <div class="quick-view-content" id="quickViewContent">
            <!-- Content will be loaded via JavaScript -->
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>

<!-- Shop Page CSS -->
<style>
/* Breadcrumb */
.breadcrumb {
    background-color: var(--light-gray);
    padding: 1rem 0;
    margin-top: 70px;
}

.breadcrumb ul {
    display: flex;
    list-style: none;
    gap: 0.5rem;
}

.breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 0.5rem;
    color: var(--gray-color);
}

.breadcrumb a {
    color: var(--gray-color);
    text-decoration: none;
    transition: var(--transition-fast);
}

.breadcrumb a:hover {
    color: var(--secondary-color);
}

.breadcrumb li:last-child {
    color: var(--dark-color);
    font-weight: 500;
}

/* Shop Header */
.shop-header {
    text-align: center;
    margin-bottom: var(--spacing-lg);
}

.page-title {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: var(--gray-color);
    font-size: 1.1rem;
}

/* Shop Content */
.shop-content {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: var(--spacing-lg);
}

/* Shop Sidebar */
.shop-sidebar {
    background-color: white;
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.filter-section {
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--light-gray);
}

.filter-section:last-child {
    border-bottom: none;
}

.filter-section h3 {
    font-size: 1.1rem;
    margin-bottom: var(--spacing-sm);
    color: var(--dark-color);
}

.filter-list {
    list-style: none;
}

.filter-list li {
    margin-bottom: 0.5rem;
}

.filter-list a {
    color: var(--gray-color);
    text-decoration: none;
    transition: var(--transition-fast);
    display: block;
    padding: 0.5rem;
    border-radius: var(--radius-sm);
}

.filter-list a:hover,
.filter-list a.active {
    color: var(--secondary-color);
    background-color: rgba(255, 77, 109, 0.1);
}

/* Price Range */
.price-range {
    padding: 1rem 0;
}

.range-slider {
    width: 100%;
    height: 4px;
    -webkit-appearance: none;
    background: var(--light-gray);
    border-radius: 2px;
    outline: none;
}

.range-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    background: var(--secondary-color);
    border-radius: 50%;
    cursor: pointer;
}

.price-values {
    display: flex;
    justify-content: space-between;
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: var(--gray-color);
}

/* Size Filters */
.size-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.size-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--light-gray);
    background: white;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition-fast);
    font-size: 0.9rem;
}

.size-btn:hover,
.size-btn.active {
    background-color: var(--dark-color);
    color: white;
    border-color: var(--dark-color);
}

/* Color Filters */
.color-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.color-btn {
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-fast);
    position: relative;
}

.color-btn:hover {
    transform: scale(1.1);
}

.color-btn.active::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.8rem;
}

/* Filter Buttons */
.filter-apply,
.filter-reset {
    width: 100%;
    margin-bottom: 0.5rem;
}

/* Shop Main */
.shop-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
    padding: 1rem;
    background-color: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.toolbar-left p {
    margin: 0;
    color: var(--gray-color);
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.sort-by {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-by label {
    color: var(--gray-color);
    font-size: 0.9rem;
}

.sort-by select {
    padding: 0.5rem;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-sm);
    background-color: white;
    font-family: var(--font-main);
    color: var(--dark-color);
}

.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--light-gray);
    background: white;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-color);
}

.view-btn:hover,
.view-btn.active {
    background-color: var(--dark-color);
    color: white;
    border-color: var(--dark-color);
}

/* Shop Grid */
.shop-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.product-card {
    position: relative;
}

.product-image {
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg);
    height: 300px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition-medium);
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.quick-view-btn {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    background-color: white;
    color: var(--dark-color);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-weight: 500;
    cursor: pointer;
    opacity: 0;
    transition: var(--transition-medium);
    z-index: 2;
    white-space: nowrap;
}

.product-card:hover .quick-view-btn {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.quick-view-btn:hover {
    background-color: var(--dark-color);
    color: white;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.product-actions .btn {
    flex: 1;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: var(--spacing-lg);
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--light-gray);
    background: white;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition-fast);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn:hover:not(:disabled) {
    background-color: var(--dark-color);
    color: white;
    border-color: var(--dark-color);
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-numbers {
    display: flex;
    gap: 0.5rem;
}

.pagination-number {
    width: 40px;
    height: 40px;
    border: 1px solid var(--light-gray);
    background: white;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition-fast);
}

.pagination-number:hover,
.pagination-number.active {
    background-color: var(--secondary-color);
    color: white;
    border-color: var(--secondary-color);
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1002;
    justify-content: center;
    align-items: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background-color: white;
    border-radius: var(--radius-xl);
    width: 90%;
    max-width: 1000px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    padding: var(--spacing-lg);
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray-color);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    background-color: var(--light-gray);
}

.quick-view-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
}

/* Responsive */
@media (max-width: 992px) {
    .shop-content {
        grid-template-columns: 1fr;
    }
    
    .shop-sidebar {
        position: static;
        margin-bottom: var(--spacing-md);
    }
    
    .shop-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-view-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .shop-toolbar {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .toolbar-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .shop-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        padding: var(--spacing-md);
    }
}
</style>

<!-- Shop Page JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price Range Slider
    const priceRange = document.getElementById('priceRange');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            const value = this.value;
            minPrice.textContent = '0';
            maxPrice.textContent = value;
        });
    }
    
    // Size Filter
    const sizeBtns = document.querySelectorAll('.size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sizeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Color Filter
    const colorBtns = document.querySelectorAll('.color-btn');
    colorBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });
    
    // View Toggle
    const viewBtns = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('productsGrid');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (view === 'list') {
                productsGrid.classList.add('list-view');
            } else {
                productsGrid.classList.remove('list-view');
            }
        });
    });
    
    // Quick View Modal
    const quickViewBtns = document.querySelectorAll('.quick-view-btn');
    const quickViewModal = document.getElementById('quickViewModal');
    const quickViewContent = document.getElementById('quickViewContent');
    const modalClose = document.querySelector('.modal-close');
    
    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            // In a real app, you would fetch product details via AJAX
            // For now, we'll show sample content
            const sampleProduct = {
                name: 'Premium Denim Jacket',
                price: '$89.99',
                originalPrice: '$120.00',
                description: 'High-quality denim jacket made from premium cotton denim. Features a modern fit, metal buttons, and multiple pockets for functionality.',
                details: '100% Cotton • Machine washable • Slim fit • Imported',
                image: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                sizes: ['S', 'M', 'L', 'XL'],
                colors: [
                    { name: 'Dark Blue', value: '#1a237e' },
                    { name: 'Light Blue', value: '#5c6bc0' },
                    { name: 'Black', value: '#000000' }
                ]
            };
            
            quickViewContent.innerHTML = `
                <div class="quick-view-image">
                    <img src="${sampleProduct.image}" alt="${sampleProduct.name}" loading="lazy">
                </div>
                <div class="quick-view-details">
                    <h2>${sampleProduct.name}</h2>
                    <div class="quick-view-price">
                        <span class="current-price">${sampleProduct.price}</span>
                        <span class="original-price">${sampleProduct.originalPrice}</span>
                    </div>
                    <div class="quick-view-description">
                        <p>${sampleProduct.description}</p>
                        <p>${sampleProduct.details}</p>
                    </div>
                    <div class="quick-view-sizes">
                        <h4>Size:</h4>
                        <div class="size-options">
                            ${sampleProduct.sizes.map(size => `<button class="size-option">${size}</button>`).join('')}
                        </div>
                    </div>
                    <div class="quick-view-colors">
                        <h4>Color:</h4>
                        <div class="color-options">
                            ${sampleProduct.colors.map(color => `
                                <button class="color-option" style="background-color: ${color.value}" title="${color.name}"></button>
                            `).join('')}
                        </div>
                    </div>
                    <div class="quick-view-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus">-</button>
                            <input type="number" value="1" min="1" max="10" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <button class="btn btn-primary add-to-cart">Add to Cart</button>
                        <button class="btn btn-outline">Add to Wishlist</button>
                    </div>
                </div>
            `;
            
            quickViewModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Close modal
    if (modalClose) {
        modalClose.addEventListener('click', function() {
            quickViewModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // Close modal when clicking outside
    quickViewModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Filter products
    const filterApply = document.querySelector('.filter-apply');
    const filterReset = document.querySelector('.filter-reset');
    
    if (filterApply) {
        filterApply.addEventListener('click', function() {
            // In a real app, this would filter products via AJAX
            alert('Filters applied! (This would filter products in a real application)');
        });
    }
    
    if (filterReset) {
        filterReset.addEventListener('click', function() {
            // Reset all filters
            sizeBtns.forEach(btn => btn.classList.remove('active'));
            colorBtns.forEach(btn => btn.classList.remove('active'));
            document.querySelector('.size-btn.active')?.classList.remove('active');
            document.querySelector('.size-btn:nth-child(3)')?.classList.add('active'); // Reset to Medium
            
            // Reset price range
            if (priceRange) {
                priceRange.value = 250;
                minPrice.textContent = '0';
                maxPrice.textContent = '250';
            }
            
            // Reset sort
            document.getElementById('sortSelect').value = 'featured';
            
            alert('Filters reset!');
        });
    }
    
    // Sort products
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // In a real app, this would sort products via AJAX
            console.log('Sorting by:', this.value);
        });
    }
});
</script>