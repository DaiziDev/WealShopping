<?php
$pageTitle = "Product Detail";
include_once '../includes/header.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Sample product data - in real app, fetch from database
$products = [
    1 => [
        'name' => 'Premium Denim Jacket',
        'category' => 'Men\'s Clothing',
        'price' => 89.99,
        'original_price' => 120.00,
        'description' => 'High-quality denim jacket made from premium cotton denim. Features a modern fit, metal buttons, and multiple pockets for both style and functionality.',
        'details' => '100% Cotton • Machine washable • Slim fit • Imported • Metal buttons',
        'features' => [
            'Premium cotton denim',
            'Modern slim fit',
            'Metal buttons and zippers',
            'Multiple functional pockets',
            'Durable construction'
        ],
        'main_image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'gallery_images' => [
            'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1593032461258-175d2d6c49c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1593032461258-175d2d6c49c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ],
        'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
        'colors' => [
            ['name' => 'Dark Blue', 'value' => '#1a237e'],
            ['name' => 'Light Blue', 'value' => '#5c6bc0'],
            ['name' => 'Black', 'value' => '#000000']
        ],
        'reviews' => [
            ['user' => 'John D.', 'rating' => 5, 'date' => '2024-01-15', 'comment' => 'Perfect fit and excellent quality!'],
            ['user' => 'Sarah M.', 'rating' => 4, 'date' => '2024-01-10', 'comment' => 'Love the jacket, but wish it had more color options.'],
            ['user' => 'Mike R.', 'rating' => 5, 'date' => '2024-01-05', 'comment' => 'Great value for money. Highly recommended!']
        ],
        'in_stock' => true,
        'sku' => 'DJ-2024-BLUE',
        'tags' => ['Jacket', 'Denim', 'Men', 'Casual', 'Premium']
    ],
    2 => [
        'name' => 'Elegant Evening Dress',
        'category' => 'Women\'s Clothing',
        'price' => 149.99,
        'original_price' => 199.99,
        'description' => 'Stunning evening dress perfect for special occasions. Made from luxurious silk blend with elegant embroidery details.',
        'details' => '70% Silk, 30% Polyester • Hand wash only • Floor length • Embroidered details',
        'features' => [
            'Luxurious silk blend',
            'Elegant embroidery',
            'Floor-length design',
            'Comfortable fit',
            'Perfect for special occasions'
        ],
        'main_image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'gallery_images' => [
            'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ],
        'sizes' => ['XS', 'S', 'M', 'L'],
        'colors' => [
            ['name' => 'Red', 'value' => '#d32f2f'],
            ['name' => 'Black', 'value' => '#000000'],
            ['name' => 'Navy', 'value' => '#0d47a1']
        ],
        'reviews' => [
            ['user' => 'Emma L.', 'rating' => 5, 'date' => '2024-01-20', 'comment' => 'Absolutely beautiful dress! Received so many compliments.'],
            ['user' => 'Lisa K.', 'rating' => 5, 'date' => '2024-01-18', 'comment' => 'Perfect for my wedding anniversary dinner.']
        ],
        'in_stock' => true,
        'sku' => 'ED-2024-RED',
        'tags' => ['Dress', 'Evening', 'Women', 'Formal', 'Luxury']
    ]
];

$product = isset($products[$productId]) ? $products[$productId] : $products[1];
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><?php echo $product['name']; ?></li>
        </ul>
    </div>
</div>

<!-- Product Detail Section -->
<section class="product-detail section-padding">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Product Images -->
            <div class="product-images">
                <div class="main-image">
                    <img src="<?php echo $product['main_image']; ?>" alt="<?php echo $product['name']; ?>" id="mainProductImage" loading="lazy">
                </div>
                <div class="gallery-thumbnails">
                    <?php foreach ($product['gallery_images'] as $index => $image): ?>
                    <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo $image; ?>">
                        <img src="<?php echo $image; ?>" alt="<?php echo $product['name']; ?> - View <?php echo $index + 1; ?>" loading="lazy">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title"><?php echo $product['name']; ?></h1>
                <div class="product-meta">
                    <span class="product-category"><?php echo $product['category']; ?></span>
                    <span class="product-sku">SKU: <?php echo $product['sku']; ?></span>
                    <span class="product-stock <?php echo $product['in_stock'] ? 'in-stock' : 'out-of-stock'; ?>">
                        <?php echo $product['in_stock'] ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                </div>
                
                <div class="product-price">
                    <span class="current-price">$<?php echo number_format($product['price'], 2); ?></span>
                    <?php if ($product['original_price']): ?>
                    <span class="original-price">$<?php echo number_format($product['original_price'], 2); ?></span>
                    <?php endif; ?>
                    <span class="discount-badge">Save <?php echo $product['original_price'] ? round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) : 0; ?>%</span>
                </div>
                
                <div class="product-description">
                    <p><?php echo $product['description']; ?></p>
                </div>
                
                <div class="product-features">
                    <h3>Features:</h3>
                    <ul>
                        <?php foreach ($product['features'] as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Product Options -->
                <form class="product-options" id="addToCartForm">
                    <!-- Size Selection -->
                    <div class="option-group">
                        <label for="sizeSelect">Size:</label>
                        <div class="size-options">
                            <?php foreach ($product['sizes'] as $size): ?>
                            <input type="radio" name="size" id="size-<?php echo $size; ?>" value="<?php echo $size; ?>" required>
                            <label for="size-<?php echo $size; ?>" class="size-option"><?php echo $size; ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Color Selection -->
                    <div class="option-group">
                        <label for="colorSelect">Color:</label>
                        <div class="color-options">
                            <?php foreach ($product['colors'] as $index => $color): ?>
                            <input type="radio" name="color" id="color-<?php echo $index; ?>" value="<?php echo $color['name']; ?>" required>
                            <label for="color-<?php echo $index; ?>" class="color-option" style="background-color: <?php echo $color['value']; ?>" title="<?php echo $color['name']; ?>"></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="option-group">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" readonly>
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="product-actions">
                        <button type="submit" class="btn btn-primary btn-lg add-to-cart" <?php echo !$product['in_stock'] ? 'disabled' : ''; ?>>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline btn-lg add-to-wishlist">
                            <i class="far fa-heart"></i> Wishlist
                        </button>
                    </div>
                </form>
                
                <!-- Product Tags -->
                <div class="product-tags">
                    <h4>Tags:</h4>
                    <div class="tags">
                        <?php foreach ($product['tags'] as $tag): ?>
                        <a href="shop.php?tag=<?php echo urlencode($tag); ?>" class="tag"><?php echo $tag; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Share Product -->
                <div class="product-share">
                    <h4>Share:</h4>
                    <div class="share-buttons">
                        <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-btn pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <a href="#" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="share-btn email"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tab-headers">
                <button class="tab-header active" data-tab="description">Description</button>
                <button class="tab-header" data-tab="details">Details</button>
                <button class="tab-header" data-tab="reviews">Reviews (<?php echo count($product['reviews']); ?>)</button>
                <button class="tab-header" data-tab="shipping">Shipping & Returns</button>
            </div>
            
            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="description">
                    <h3>Product Description</h3>
                    <p><?php echo $product['description']; ?></p>
                    <p><?php echo $product['details']; ?></p>
                    
                    <div class="feature-grid">
                        <?php foreach ($product['features'] as $feature): ?>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo $feature; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Details Tab -->
                <div class="tab-pane" id="details">
                    <h3>Product Details</h3>
                    <div class="details-table">
                        <div class="detail-row">
                            <span class="detail-label">Material</span>
                            <span class="detail-value">100% Premium Cotton</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Care Instructions</span>
                            <span class="detail-value">Machine washable, tumble dry low</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Fit</span>
                            <span class="detail-value">Slim fit</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Origin</span>
                            <span class="detail-value">Imported</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Closure</span>
                            <span class="detail-value">Button front</span>
                        </div>
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane" id="reviews">
                    <h3>Customer Reviews</h3>
                    
                    <div class="reviews-summary">
                        <div class="average-rating">
                            <div class="rating-stars">
                                <?php
                                $avgRating = array_sum(array_column($product['reviews'], 'rating')) / count($product['reviews']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($avgRating)) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($i - 0.5 <= $avgRating) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <span class="rating-number"><?php echo number_format($avgRating, 1); ?> out of 5</span>
                        </div>
                        <button class="btn btn-primary write-review-btn">Write a Review</button>
                    </div>
                    
                    <div class="reviews-list">
                        <?php foreach ($product['reviews'] as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <span class="reviewer-name"><?php echo $review['user']; ?></span>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <span class="review-date"><?php echo date('F j, Y', strtotime($review['date'])); ?></span>
                            </div>
                            <div class="review-content">
                                <p><?php echo $review['comment']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Shipping Tab -->
                <div class="tab-pane" id="shipping">
                    <h3>Shipping & Returns</h3>
                    
                    <div class="shipping-info">
                        <h4>Shipping Information</h4>
                        <ul>
                            <li>Free standard shipping on orders over $100</li>
                            <li>Express shipping available for $15</li>
                            <li>Estimated delivery: 3-7 business days</li>
                            <li>International shipping available</li>
                        </ul>
                    </div>
                    
                    <div class="returns-info">
                        <h4>Returns Policy</h4>
                        <ul>
                            <li>30-day return policy</li>
                            <li>Items must be unused and in original packaging</li>
                            <li>Free returns for defective items</li>
                            <li>Refunds processed within 5-10 business days</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <div class="related-products">
            <h2 class="section-title">You May Also Like</h2>
            
            <div class="products-grid">
                <?php
                $relatedProducts = [
                    [
                        'id' => 3,
                        'name' => 'Leather Running Shoes',
                        'category' => 'Footwear',
                        'price' => 129.99,
                        'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        'badge' => null
                    ],
                    [
                        'id' => 5,
                        'name' => 'Casual T-Shirt',
                        'category' => 'Men\'s Clothing',
                        'price' => 29.99,
                        'original_price' => 39.99,
                        'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        'badge' => 'Sale'
                    ],
                    [
                        'id' => 7,
                        'name' => 'Formal Dress Shoes',
                        'category' => 'Footwear',
                        'price' => 159.99,
                        'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        'badge' => null
                    ],
                    [
                        'id' => 11,
                        'name' => 'Casual Sneakers',
                        'category' => 'Footwear',
                        'price' => 89.99,
                        'original_price' => 119.99,
                        'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        'badge' => 'Sale'
                    ]
                ];
                
                foreach ($relatedProducts as $relatedProduct) {
                    $badgeClass = '';
                    if ($relatedProduct['badge'] == 'Sale') {
                        $badgeClass = 'sale';
                    }
                    
                    echo '
                    <div class="product-card">
                        <div class="product-image">
                            <img src="' . $relatedProduct['image'] . '" alt="' . $relatedProduct['name'] . '" loading="lazy">
                            ' . ($relatedProduct['badge'] ? '<span class="product-badge ' . $badgeClass . '">' . $relatedProduct['badge'] . '</span>' : '') . '
                            <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><a href="product-detail.php?id=' . $relatedProduct['id'] . '">' . $relatedProduct['name'] . '</a></h3>
                            <p class="product-category">' . $relatedProduct['category'] . '</p>
                            <div class="product-price">
                                <span class="current-price">$' . number_format($relatedProduct['price'], 2) . '</span>
                                ' . (isset($relatedProduct['original_price']) ? '<span class="original-price">$' . number_format($relatedProduct['original_price'], 2) . '</span>' : '') . '
                            </div>
                            <button class="btn btn-sm add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>

<!-- Product Detail Page CSS -->
<style>
/* Product Detail Grid */
.product-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

/* Product Images */
.product-images {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.main-image {
    border-radius: var(--radius-xl);
    overflow: hidden;
    height: 500px;
    background-color: var(--light-gray);
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-thumbnails {
    display: flex;
    gap: var(--spacing-sm);
    overflow-x: auto;
    padding-bottom: 10px;
}

.gallery-thumbnails::-webkit-scrollbar {
    height: 4px;
}

.gallery-thumbnails::-webkit-scrollbar-thumb {
    background-color: var(--light-gray);
    border-radius: 2px;
}

.thumbnail {
    width: 100px;
    height: 100px;
    border-radius: var(--radius-md);
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    flex-shrink: 0;
    transition: var(--transition-fast);
}

.thumbnail:hover,
.thumbnail.active {
    border-color: var(--secondary-color);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.product-info {
    padding: var(--spacing-md);
}

.product-title {
    font-size: 2.5rem;
    margin-bottom: var(--spacing-sm);
    color: var(--dark-color);
}

.product-meta {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    color: var(--gray-color);
    font-size: 0.9rem;
}

.product-category {
    color: var(--secondary-color);
    font-weight: 500;
}

.product-stock {
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 500;
}

.product-stock.in-stock {
    background-color: rgba(76, 175, 80, 0.1);
    color: #4CAF50;
}

.product-stock.out-of-stock {
    background-color: rgba(244, 67, 54, 0.1);
    color: #F44336;
}

/* Product Price */
.product-price {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}

.product-price .current-price {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
}

.product-price .original-price {
    font-size: 1.2rem;
    color: var(--gray-color);
    text-decoration: line-through;
}

.discount-badge {
    background-color: var(--secondary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
    font-weight: 500;
}

/* Product Description */
.product-description {
    margin-bottom: var(--spacing-md);
    line-height: 1.8;
}

.product-features {
    margin-bottom: var(--spacing-md);
}

.product-features h3 {
    font-size: 1.2rem;
    margin-bottom: var(--spacing-sm);
}

.product-features ul {
    list-style: none;
    padding-left: 0;
}

.product-features li {
    margin-bottom: 0.5rem;
    padding-left: 1.5rem;
    position: relative;
}

.product-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--secondary-color);
    font-weight: bold;
}

/* Product Options */
.option-group {
    margin-bottom: var(--spacing-md);
}

.option-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.size-options {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.size-options input[type="radio"] {
    display: none;
}

.size-option {
    width: 50px;
    height: 50px;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-fast);
    background-color: white;
}

.size-option:hover {
    border-color: var(--dark-color);
}

.size-options input[type="radio"]:checked + .size-option {
    background-color: var(--dark-color);
    color: white;
    border-color: var(--dark-color);
}

.color-options {
    display: flex;
    gap: 0.5rem;
}

.color-options input[type="radio"] {
    display: none;
}

.color-option {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-fast);
    position: relative;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-options input[type="radio"]:checked + .color-option::after {
    content: '';
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border: 2px solid var(--dark-color);
    border-radius: 50%;
}

.quantity-selector {
    display: flex;
    align-items: center;
    max-width: 150px;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--light-gray);
    background-color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: var(--transition-fast);
}

.quantity-btn:hover {
    background-color: var(--light-gray);
}

.quantity-btn.minus {
    border-radius: var(--radius-sm) 0 0 var(--radius-sm);
}

.quantity-btn.plus {
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
}

.quantity-input {
    width: 60px;
    height: 40px;
    border: 1px solid var(--light-gray);
    border-left: none;
    border-right: none;
    text-align: center;
    font-family: var(--font-main);
    font-size: 1rem;
    color: var(--dark-color);
    background-color: white;
}

/* Product Actions */
.product-actions {
    display: flex;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}

.product-actions .btn {
    flex: 1;
    padding: 1rem;
    font-size: 1.1rem;
}

.product-actions .btn-lg {
    padding: 1rem 2rem;
}

/* Product Tags */
.product-tags {
    margin-bottom: var(--spacing-md);
}

.product-tags h4 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag {
    padding: 0.5rem 1rem;
    background-color: var(--light-gray);
    color: var(--dark-color);
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition-fast);
}

.tag:hover {
    background-color: var(--secondary-color);
    color: white;
}

/* Product Share */
.product-share h4 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.share-buttons {
    display: flex;
    gap: 0.5rem;
}

.share-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--transition-fast);
}

.share-btn.facebook { background-color: #3b5998; }
.share-btn.twitter { background-color: #1da1f2; }
.share-btn.pinterest { background-color: #bd081c; }
.share-btn.whatsapp { background-color: #25d366; }
.share-btn.email { background-color: var(--gray-color); }

.share-btn:hover {
    transform: translateY(-3px);
    opacity: 0.9;
}

/* Product Tabs */
.product-tabs {
    margin: var(--spacing-lg) 0;
    background-color: white;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.tab-headers {
    display: flex;
    border-bottom: 1px solid var(--light-gray);
    background-color: var(--light-color);
}

.tab-header {
    padding: 1rem 2rem;
    background: none;
    border: none;
    font-family: var(--font-main);
    font-size: 1rem;
    font-weight: 500;
    color: var(--gray-color);
    cursor: pointer;
    transition: var(--transition-fast);
    position: relative;
}

.tab-header:hover {
    color: var(--dark-color);
}

.tab-header.active {
    color: var(--secondary-color);
}

.tab-header.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: var(--secondary-color);
}

.tab-content {
    padding: var(--spacing-lg);
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

.tab-pane h3 {
    margin-bottom: var(--spacing-md);
    color: var(--dark-color);
}

/* Feature Grid */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.feature-item i {
    color: var(--secondary-color);
}

/* Details Table */
.details-table {
    display: grid;
    gap: 0.5rem;
}

.detail-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--light-gray);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    color: var(--dark-color);
}

.detail-value {
    color: var(--gray-color);
}

/* Reviews */
.reviews-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding: var(--spacing-md);
    background-color: var(--light-color);
    border-radius: var(--radius-lg);
}

.average-rating {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.rating-stars {
    color: #ffc107;
}

.rating-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-color);
}

.write-review-btn {
    padding: 0.75rem 1.5rem;
}

/* Reviews List */
.reviews-list {
    display: grid;
    gap: var(--spacing-md);
}

.review-item {
    padding: var(--spacing-md);
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-lg);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-sm);
}

.reviewer-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.reviewer-name {
    font-weight: 500;
    color: var(--dark-color);
}

.review-rating {
    color: #ffc107;
}

.review-rating .filled {
    color: #ffc107;
}

.review-date {
    color: var(--gray-color);
    font-size: 0.9rem;
}

.review-content p {
    margin: 0;
    line-height: 1.6;
}

/* Shipping Info */
.shipping-info,
.returns-info {
    margin-bottom: var(--spacing-lg);
}

.shipping-info h4,
.returns-info h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--dark-color);
}

.shipping-info ul,
.returns-info ul {
    list-style: none;
    padding-left: 1.5rem;
}

.shipping-info li,
.returns-info li {
    margin-bottom: 0.5rem;
    position: relative;
}

.shipping-info li::before,
.returns-info li::before {
    content: '•';
    position: absolute;
    left: -1rem;
    color: var(--secondary-color);
}

/* Related Products */
.related-products {
    margin-top: var(--spacing-xl);
}

.related-products .section-title {
    text-align: left;
    margin-bottom: var(--spacing-lg);
}

.related-products .section-title::after {
    left: 0;
    transform: none;
}

.related-products .products-grid {
    grid-template-columns: repeat(4, 1fr);
}

/* Responsive */
@media (max-width: 1200px) {
    .related-products .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .product-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .main-image {
        height: 400px;
    }
    
    .related-products .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .feature-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-row {
        grid-template-columns: 1fr;
        gap: 0.25rem;
    }
}

@media (max-width: 768px) {
    .product-title {
        font-size: 2rem;
    }
    
    .product-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .product-actions {
        flex-direction: column;
    }
    
    .tab-headers {
        flex-wrap: wrap;
    }
    
    .tab-header {
        flex: 1;
        min-width: 50%;
        text-align: center;
        padding: 1rem;
    }
    
    .reviews-summary {
        flex-direction: column;
        gap: var(--spacing-sm);
        text-align: center;
    }
    
    .related-products .products-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .main-image {
        height: 300px;
    }
    
    .gallery-thumbnails {
        justify-content: center;
    }
    
    .tab-header {
        min-width: 100%;
    }
    
    .share-buttons {
        justify-content: center;
    }
}
</style>

<!-- Product Detail JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Gallery
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Update main image
            const newImage = this.dataset.image;
            mainImage.src = newImage;
            
            // Update active thumbnail
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Size Selection
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all size options
            document.querySelectorAll('.size-option').forEach(o => o.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Check the corresponding radio button
            const radio = document.getElementById(this.htmlFor);
            if (radio) {
                radio.checked = true;
            }
        });
    });
    
    // Color Selection
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove border from all color options
            document.querySelectorAll('.color-option').forEach(o => {
                o.style.border = 'none';
                o.style.boxShadow = 'none';
            });
            
            // Add border to clicked option
            this.style.boxShadow = '0 0 0 3px rgba(255, 77, 109, 0.3)';
            
            // Check the corresponding radio button
            const radio = document.getElementById(this.htmlFor);
            if (radio) {
                radio.checked = true;
            }
        });
    });
    
    // Quantity Selector
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.getElementById('quantity');
    
    if (minusBtn && plusBtn && quantityInput) {
        minusBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
        
        plusBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value < 10) {
                quantityInput.value = value + 1;
            }
        });
    }
    
    // Add to Cart Form
    const addToCartForm = document.getElementById('addToCartForm');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const productId = <?php echo $productId; ?>;
            const productName = "<?php echo addslashes($product['name']); ?>";
            const price = <?php echo $product['price']; ?>;
            const size = formData.get('size');
            const color = formData.get('color');
            const quantity = formData.get('quantity');
            
            // In a real app, you would send this data to the server
            // For now, we'll just show a success message
            alert(`Added to cart:\n${productName}\nSize: ${size}\nColor: ${color}\nQuantity: ${quantity}\nPrice: $${(price * quantity).toFixed(2)}`);
            
            // Update cart count
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                let currentCount = parseInt(cartCount.textContent);
                cartCount.textContent = currentCount + parseInt(quantity);
                
                // Animation
                cartCount.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    cartCount.style.transform = 'scale(1)';
                }, 300);
            }
        });
    }
    
    // Add to Wishlist
    const addToWishlistBtn = document.querySelector('.add-to-wishlist');
    if (addToWishlistBtn) {
        addToWishlistBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
                alert('Added to wishlist!');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.innerHTML = '<i class="far fa-heart"></i> Wishlist';
                alert('Removed from wishlist!');
            }
        });
    }
    
    // Tab Switching
    const tabHeaders = document.querySelectorAll('.tab-header');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update active tab header
            tabHeaders.forEach(h => h.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding tab pane
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
                if (pane.id === tabId) {
                    pane.classList.add('active');
                }
            });
        });
    });
    
    // Write Review Button
    const writeReviewBtn = document.querySelector('.write-review-btn');
    if (writeReviewBtn) {
        writeReviewBtn.addEventListener('click', function() {
            alert('Review form would open here in a real application.');
        });
    }
    
    // Share Buttons
    const shareButtons = document.querySelectorAll('.share-btn');
    const productUrl = window.location.href;
    const productTitle = document.querySelector('.product-title').textContent;
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            let shareUrl = '#';
            const platform = Array.from(this.classList).find(cls => cls.includes('facebook') || cls.includes('twitter') || cls.includes('pinterest') || cls.includes('whatsapp') || cls.includes('email'));
            
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(productUrl)}&text=${encodeURIComponent(productTitle)}`;
                    break;
                case 'pinterest':
                    shareUrl = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(productUrl)}&description=${encodeURIComponent(productTitle)}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(productTitle + ' ' + productUrl)}`;
                    break;
                case 'email':
                    shareUrl = `mailto:?subject=${encodeURIComponent(productTitle)}&body=${encodeURIComponent('Check out this product: ' + productUrl)}`;
                    break;
            }
            
            window.open(shareUrl, '_blank', 'width=600,height=400');
        });
    });
});
</script>