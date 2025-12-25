<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EliteStyle | Premium Fashion Store</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <span class="logo-text">Elite</span><span class="logo-accent">Style</span>
            </a>
            
            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link active">Home</a></li>
                <li class="nav-item"><a href="pages/shop.php" class="nav-link">Clothing</a></li>
                <li class="nav-item"><a href="pages/shop.php?category=shoes" class="nav-link">Shoes</a></li>
                <li class="nav-item"><a href="pages/shop.php?category=accessories" class="nav-link">Accessories</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            
            <!-- Icons -->
            <div class="nav-icons">
                <button class="icon-btn search-btn" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                <a href="pages/cart.php" class="icon-btn cart-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count">0</span>
                </a>
                <button class="icon-btn user-btn">
                    <i class="fas fa-user"></i>
                </button>
                <button class="menu-toggle" id="menuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" placeholder="Search for products, brands, and more...">
                <button class="search-close"><i class="fas fa-times"></i></button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <ul class="mobile-nav">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="pages/shop.php">Clothing</a></li>
                <li><a href="pages/shop.php?category=shoes">Shoes</a></li>
                <li><a href="pages/shop.php?category=accessories">Accessories</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-slider">
            <div class="slide active">
                <div class="slide-content">
                    <h1 class="slide-title">New Collection 2024</h1>
                    <p class="slide-subtitle">Discover exclusive designs for the modern lifestyle</p>
                    <a href="pages/shop.php" class="btn btn-primary">Shop Now</a>
                </div>
                <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1445205170230-053b83016050?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');">
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1 class="slide-title">Premium Footwear</h1>
                    <p class="slide-subtitle">Step into comfort with our luxury shoe collection</p>
                    <a href="pages/shop.php?category=shoes" class="btn btn-primary">Explore Shoes</a>
                </div>
                <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');">
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1 class="slide-title">Summer Essentials</h1>
                    <p class="slide-subtitle">Lightweight fabrics and vibrant colors</p>
                    <a href="pages/shop.php?category=clothing" class="btn btn-primary">View Collection</a>
                </div>
                <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');">
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
                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80');">
                    </div>
                    <div class="category-content">
                        <h3>Men's Fashion</h3>
                        <p>Contemporary styles for the modern man</p>
                        <a href="pages/shop.php?category=men" class="btn btn-outline">Browse</a>
                    </div>
                </div>
                
                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80');">
                    </div>
                    <div class="category-content">
                        <h3>Women's Collection</h3>
                        <p>Elegant designs for every occasion</p>
                        <a href="pages/shop.php?category=women" class="btn btn-outline">Browse</a>
                    </div>
                </div>
                
                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80');">
                    </div>
                    <div class="category-content">
                        <h3>Footwear</h3>
                        <p>From casual sneakers to formal shoes</p>
                        <a href="pages/shop.php?category=shoes" class="btn btn-outline">Browse</a>
                    </div>
                </div>
                
                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1590649887895-9d5d3b8f9a48?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80');">
                    </div>
                    <div class="category-content">
                        <h3>Accessories</h3>
                        <p>Complete your look with our accessories</p>
                        <a href="pages/shop.php?category=accessories" class="btn btn-outline">Browse</a>
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
                <a href="pages/shop.php" class="btn-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image" style="background-image: url('https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80');">
                        <span class="product-badge">New</span>
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Premium Denim Jacket</h3>
                        <p class="product-category">Men's Clothing</p>
                        <div class="product-price">
                            <span class="current-price">$89.99</span>
                            <span class="original-price">$120.00</span>
                        </div>
                        <button class="btn btn-sm add-to-cart">Add to Cart</button>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image" style="background-image: url('https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80');">
                        <span class="product-badge sale">Sale</span>
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Elegant Evening Dress</h3>
                        <p class="product-category">Women's Clothing</p>
                        <div class="product-price">
                            <span class="current-price">$149.99</span>
                            <span class="original-price">$199.99</span>
                        </div>
                        <button class="btn btn-sm add-to-cart">Add to Cart</button>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image" style="background-image: url('https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80');">
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Leather Running Shoes</h3>
                        <p class="product-category">Footwear</p>
                        <div class="product-price">
                            <span class="current-price">$129.99</span>
                        </div>
                        <button class="btn btn-sm add-to-cart">Add to Cart</button>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image" style="background-image: url('https://images.unsplash.com/photo-1584917865442-de89df76afd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80');">
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Designer Handbag</h3>
                        <p class="product-category">Accessories</p>
                        <div class="product-price">
                            <span class="current-price">$199.99</span>
                        </div>
                        <button class="btn btn-sm add-to-cart">Add to Cart</button>
                    </div>
                </div>
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
                    <p>EliteStyle was founded with a simple mission: to provide premium fashion that combines style, comfort, and sustainability. We believe that everyone deserves to feel confident and express their unique personality through clothing.</p>
                    <p>Our collections are carefully curated from designers around the world, focusing on quality materials and timeless designs that transcend seasonal trends.</p>
                    <div class="about-features">
                        <div class="feature">
                            <i class="fas fa-leaf"></i>
                            <h4>Sustainable Materials</h4>
                            <p>Ethically sourced and eco-friendly fabrics</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-award"></i>
                            <h4>Premium Quality</h4>
                            <p>Exceptional craftsmanship in every detail</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-shipping-fast"></i>
                            <h4>Fast Shipping</h4>
                            <p>Free delivery on orders over $100</p>
                        </div>
                    </div>
                </div>
                <div class="about-image" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80');">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="index.php" class="logo">
                        <span class="logo-text">Elite</span><span class="logo-accent">Style</span>
                    </a>
                    <p class="footer-description">Premium fashion for the modern lifestyle. Quality, style, and sustainability at your fingertips.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest-p"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="pages/shop.php">Shop</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Best Sellers</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Size Guide</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Fashion Street, New York, NY 10001</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@elitestyle.com</li>
                    </ul>
                    <div class="newsletter">
                        <h4>Newsletter</h4>
                        <p>Subscribe for updates and exclusive offers</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your email address" required>
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 EliteStyle. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
                <div class="payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-paypal"></i>
                    <i class="fab fa-cc-apple-pay"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/animations.js"></script>
    <script src="assets/js/cart.js"></script>
</body>
</html>