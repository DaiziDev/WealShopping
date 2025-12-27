    <!-- Footer -->
    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="<?php echo site_url('index.php'); ?>" class="logo">
                        <span class="logo-text2">Weal</span><span class="logo-accent">Shopping</span>
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
                        <li><a href="<?php echo site_url('index.php'); ?>">Home</a></li>
                        <li><a href="<?php echo page_url('shop.php'); ?>">Shop</a></li>
                        <li><a href="<?php echo site_url('index.php'); ?>#about">About Us</a></li>
                        <li><a href="<?php echo page_url('shop.php?new=1'); ?>">New Arrivals</a></li>
                        <li><a href="<?php echo page_url('shop.php?bestseller=1'); ?>">Best Sellers</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="<?php echo page_url('contact.php'); ?>">Contact Us</a></li>
                        <li><a href="<?php echo page_url('faq.php'); ?>">FAQ</a></li>
                        <li><a href="<?php echo page_url('shipping.php'); ?>">Shipping Policy</a></li>
                        <li><a href="<?php echo page_url('returns.php'); ?>">Returns & Exchanges</a></li>
                        <li><a href="<?php echo page_url('size-guide.php'); ?>">Size Guide</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Fashion Street, New York, NY 10001</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@wealshopping.com</li>
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
                <p>&copy; 2024 WealShopping. All rights reserved. | <a href="<?php echo page_url('privacy.php'); ?>">Privacy Policy</a> | <a href="<?php echo page_url('terms.php'); ?>">Terms of Service</a></p>
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

    <!-- JavaScript with fallback paths -->
    <?php 
    // Get correct base URL
    $base_url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $base_url .= $_SERVER['HTTP_HOST'];
    $base_dir = dirname($_SERVER['SCRIPT_NAME']);
    if ($base_dir != '/') {
        $base_url .= $base_dir;
    }
    ?>
    
    <!-- Main JavaScript -->
    <script>
        // Debug: Check current page
        console.log('Current page:', window.location.pathname);
        console.log('Document readyState:', document.readyState);
        
        // Test if menu toggle exists
        const menuToggleTest = document.getElementById('menuToggle');
        console.log('Menu toggle exists:', !!menuToggleTest);
        
        // Force mobile menu initialization
        function forceMobileMenuInit() {
            console.log('Force initializing mobile menu...');
            const toggle = document.getElementById('menuToggle');
            const menu = document.querySelector('.mobile-menu');
            
            if (toggle && menu) {
                console.log('✅ Found elements, attaching click handler');
                
                // Remove any existing click handlers
                const newToggle = toggle.cloneNode(true);
                toggle.parentNode.replaceChild(newToggle, toggle);
                
                // Re-get references
                const currentToggle = document.getElementById('menuToggle');
                const currentMenu = document.querySelector('.mobile-menu');
                
                // Add click handler
                currentToggle.addEventListener('click', function(e) {
                    console.log('FORCE: Menu toggle clicked!');
                    e.stopPropagation();
                    e.preventDefault();
                    
                    this.classList.toggle('active');
                    currentMenu.classList.toggle('active');
                    
                    if (currentMenu.classList.contains('active')) {
                        document.body.style.overflow = 'hidden';
                        document.body.classList.add('menu-open');
                    } else {
                        document.body.style.overflow = '';
                        document.body.classList.remove('menu-open');
                    }
                });
                
                console.log('✅ Force initialization complete');
            } else {
                console.error('❌ Elements not found for force init');
            }
        }
        
        // Run force initialization after a delay
        setTimeout(forceMobileMenuInit, 500);
    </script>
    
    <!-- Load main.js with absolute path -->
    <script src="/fashion-shop/assets/js/main.js"></script>
    
    <!-- Load other scripts -->
    <script src="<?php echo asset_url('js/animations.js'); ?>"></script>
    <script src="<?php echo asset_url('js/cart.js'); ?>"></script>
    
    <?php if (basename($_SERVER['PHP_SELF']) == 'product-detail.php'): ?>
    <script src="<?php echo asset_url('js/product-detail.js'); ?>"></script>
    <?php endif; ?>
    
    <?php if (basename($_SERVER['PHP_SELF']) == 'checkout.php'): ?>
    <script src="<?php echo asset_url('js/checkout.js'); ?>"></script>
    <?php endif; ?>
    
</body>
</html>