<?php
require_once 'includes/header.php';

// Get featured products
$featured_products = getFeaturedProducts(8);

// Get categories
$categories = getCategories();
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-slider">
            <div class="slide active">
                <div class="slide-content">
                    <h1 class="slide-title">New Collection 2024</h1>
                    <p class="slide-subtitle">Discover exclusive designs for the modern lifestyle</p>
                    <a href="pages/shop.php" class="btn btn-primary">Shop Now</a>
                </div>
                <div class="slide-image" style="background-image: url('assets/images/woman-shopping-thrift-store.jpg');">
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1 class="slide-title">Premium Footwear</h1>
                    <p class="slide-subtitle">Step into comfort with our luxury shoe collection</p>
                    <a href="pages/shop.php?category=footwear" class="btn btn-primary">Explore Shoes</a>
                </div>
                <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');">
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1 class="slide-title">Summer Essentials</h1>
                    <p class="slide-subtitle">Lightweight fabrics and vibrant colors</p>
                    <a href="pages/shop.php" class="btn btn-primary">View Collection</a>
                </div>
                <div class="slide-image" style="background-image: url('assets/images/model-career-kit-still-life.jpg');">
                </div>
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
            <div class="slider-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories section-padding">
        <div class="container">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Find exactly what you're looking for</p>
            
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                <div class="category-card">
                    <div class="category-image" style="background-image: url('<?php echo $category['image_url'] ?: 'assets/images/still-life-rendering-jackets-display.jpg'; ?>');">
                    </div>
                    <div class="category-content">
                        <h3><?php echo $category['name']; ?></h3>
                        <p><?php echo $category['description']; ?></p>
                        <a href="pages/shop.php?category=<?php echo $category['slug']; ?>" class="btn btn-outline">Browse</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Curated selections of our finest items</p>
                <a href="pages/shop.php" class="btn-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="products-grid">
                <?php if (count($featured_products) > 0): ?>
                    <?php foreach ($featured_products as $product): 
                        // Get default image if not available
                        $image_url = !empty($product['image_url']) ? $product['image_url'] : 'assets/images/still-life-rendering-jackets-display.jpg';
                    ?>
                    <div class="product-card">
                        <div class="product-image" style="background-image: url('<?php echo $image_url; ?>');">
                            <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                            <span class="product-badge sale">Sale</span>
                            <?php else: ?>
                            <span class="product-badge">New</span>
                            <?php endif; ?>
                            <button class="wishlist-btn" data-product-id="<?php echo $product['id']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['brand'] ?: 'EliteStyle'); ?></p>
                            <div class="product-price">
                                <span class="current-price">$<?php echo number_format($product['price'], 2); ?></span>
                                <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                <span class="original-price">$<?php echo number_format($product['compare_price'], 2); ?></span>
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
                        <p>No featured products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Promo Banner -->
    <section class="promo-banner">
        <div class="container">
            <div class="promo-content">
                <h2>Limited Time Offer</h2>
                <p>Get 30% off on all summer collection items. Use code: <strong>SUMMER30</strong></p>
                <a href="pages/shop.php" class="btn btn-light">Shop the Sale</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about section-padding" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2 class="section-title">Our Story</h2>
                    <p>WealShopping was founded with a simple mission: to provide premium fashion that combines style, comfort, and sustainability. We believe that everyone deserves to feel confident and express their unique personality through clothing.</p>
                    <p>Our collections are carefully curated from designers around the world, focusing on quality materials and timeless designs that transcend seasonal trends.</p>
                    <div class="about-features">
                        <div class="feature">
                            <i class="fas fa-truck"></i>
                            <h4>Fast Delivery in Cameroon</h4>
                            <p>Same-day delivery in major cities</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-mobile-alt"></i>
                            <h4>Mobile Money Payments</h4>
                            <p>Pay with MTN or Orange Money</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Secure Shopping</h4>
                            <p>100% secure payment gateway</p>
                        </div>
                    </div>
                </div>
                <div class="about-image" style="background-image: url('assets/images/still-life-rendering-jackets-display.jpg');">
                </div>
            </div>
        </div>
    </section>

<?php
require_once 'includes/footer.php';
?>