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
                    <h1 class="slide-title">New Collection 2026</h1>
                    <p class="slide-subtitle">Discover exclusive designs for the modern lifestyle</p>
                    <a href="<?php echo page_url('shop.php'); ?>" class="btn btn-primary">Shop Now</a>
                </div>
                <div class="slide-image" style="background-image: url('<?php echo asset_url('images/woman-shopping-thrift-store.jpg'); ?>');">
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1 class="slide-title">Premium Footwear</h1>
                    <p class="slide-subtitle">Step into comfort with our luxury shoe collection</p>
                    <a href="<?php echo page_url('shop.php?category=footwear'); ?>" class="btn btn-primary">Explore Shoes</a>
                </div>
                <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');">
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1 class="slide-title">Summer Essentials</h1>
                    <p class="slide-subtitle">Lightweight fabrics and vibrant colors</p>
                    <a href="<?php echo page_url('shop.php'); ?>" class="btn btn-primary">View Collection</a>
                </div>
                <div class="slide-image" style="background-image: url('../fashion-shop/assets/images/accesories.jpg');">
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
                <!-- Category 1: Clothing -->
                <div class="category-card">
                    <div class="category-image" style="background-image: url('<?php echo asset_url('images/womens.jpg'); ?>');">
                    </div>
                    <div class="category-content">
                        <h3>Clothing</h3>
                        <p>Discover our premium clothing collection for all occasions</p>
                        <a href="<?php echo page_url('shop.php?category=clothing'); ?>" class="btn btn-outline">Browse Clothing</a>
                    </div>
                </div>
                
                <!-- Category 2: Footwear -->
                <div class="category-card">
                    <div class="category-image" style="background-image: url('<?php echo asset_url('images/footwear.jpg'); ?>');">
                    </div>
                    <div class="category-content">
                        <h3>Footwear</h3>
                        <p>Stylish and comfortable shoes for every step</p>
                        <a href="<?php echo page_url('shop.php?category=footwear'); ?>" class="btn btn-outline">Browse Shoes</a>
                    </div>
                </div>
                
                <!-- Category 3: Accessories -->
                <div class="category-card">
                    <div class="category-image" style="background-image: url('<?php echo asset_url('images/accesories2.jpg'); ?>');">
                    </div>
                    <div class="category-content">
                        <h3>Accessories</h3>
                        <p>Complete your look with our premium accessories</p>
                        <a href="<?php echo page_url('shop.php?category=accessories'); ?>" class="btn btn-outline">Browse Accessories</a>
                    </div>
                </div>
                
                <!-- Category 4: Bags -->
                <div class="category-card">
                    <div class="category-image" style="background-image: url('<?php echo asset_url('images/bags.jpg'); ?>');">
                    </div>
                    <div class="category-content">
                        <h3>Bags & Luggage</h3>
                        <p>Carry your essentials in style with our bag collection</p>
                        <a href="<?php echo page_url('shop.php?category=bags'); ?>" class="btn btn-outline">Browse Bags</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Curated selections of our finest items</p>
                <a href="<?php echo page_url('shop.php'); ?>" class="btn-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="products-grid">
                <?php if (count($featured_products) > 0): ?>
                    <?php foreach ($featured_products as $product): 
                        // Get default image if not available
                        $image_url = !empty($product['image_url']) ? $product['image_url'] : asset_url('images/still-life-rendering-jackets-display.jpg');
                        
                        // Format prices in FCFA
                        $price_fcfa = $product['price'];
                        $compare_price_fcfa = $product['compare_price'] ? $product['compare_price'] : null;
                    ?>
                    <div class="product-card">
                        <div class="product-image" style="background-image: url('<?php echo $image_url; ?>');">
                            <?php if ($compare_price_fcfa && $compare_price_fcfa > $price_fcfa): ?>
                            <span class="product-badge sale">Promotion</span>
                            <?php else: ?>
                            <span class="product-badge">Nouveau</span>
                            <?php endif; ?>
                            <button class="wishlist-btn" data-product-id="<?php echo $product['id']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['brand'] ?: 'EliteStyle'); ?></p>
                            <div class="product-price">
                                <span class="current-price"><?php echo format_price($price_fcfa); ?></span>
                                <?php if ($compare_price_fcfa && $compare_price_fcfa > $price_fcfa): ?>
                                <span class="original-price"><?php echo format_price($compare_price_fcfa); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-sm add-to-cart" 
                                    data-product-id="<?php echo $product['id']; ?>" 
                                    data-product-name="<?php echo htmlspecialchars($product['name']); ?>" 
                                    data-product-price="<?php echo $price_fcfa; ?>"
                                    data-image-url="<?php echo htmlspecialchars($image_url); ?>">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products">
                        <p>Aucun produit en vedette disponible pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    <section class="promo-banner">
        <div class="container">
            <div class="promo-content">
                <h2>Offre limitée</h2>
                <p>Obtenez 30% de réduction sur tous les articles de la collection été. Code : <strong>ETE30</strong></p>
                <a href="<?php echo page_url('shop.php'); ?>" class="btn btn-light">Profitez des soldes</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about section-padding" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2 class="section-title">Notre Histoire</h2>
                    <p>WealShopping a été fondé avec une mission simple : fournir des vêtements de qualité qui allient style, confort et durabilité. Nous croyons que tout le monde mérite de se sentir en confiance et d'exprimer sa personnalité unique à travers ses vêtements.</p>
                    <p>Nos collections sont soigneusement sélectionnées auprès de designers du monde entier, en mettant l'accent sur des matériaux de qualité et des designs intemporels qui transcendent les tendances saisonnières.</p>
                    <div class="about-features">
                        <div class="feature">
                            <i class="fas fa-truck"></i>
                            <h4>Livraison rapide au Cameroun</h4>
                            <p>Livraison le jour même dans les grandes villes</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-mobile-alt"></i>
                            <h4>Paiements Mobile Money</h4>
                            <p>Payez avec MTN ou Orange Money</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Achats sécurisés</h4>
                            <p>Passerelle de paiement 100% sécurisée</p>
                        </div>
                    </div>
                </div>
                <div class="about-image" style="background-image: url('<?php echo asset_url('images/WealShopping.png'); ?>');">
                </div>
            </div>
        </div>
    </section>

<?php
require_once 'includes/footer.php';
?>