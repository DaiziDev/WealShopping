// Main JavaScript File for WealShopping

console.log('Main.js loaded successfully');

// Global flag to prevent multiple initializations
let mobileMenuInitialized = false;

// Initialize everything
function initializeAll() {
    console.log('Initializing all features...');
    
    // Hide preloader
    initPreloader();
    
    // Initialize mobile menu (with conflict protection)
    initMobileMenuSafe();
    
    // Initialize other features
    initSearchToggle();
    initBackToTop();
    initSlider();
    initAddToCart();
    initWishlist();
    initTabs();
    initQuantityControls();
    
    console.log('All features initialized');
}

// Safe mobile menu initialization
function initMobileMenuSafe() {
    if (mobileMenuInitialized) {
        console.log('Mobile menu already initialized');
        return;
    }
    
    console.log('Initializing mobile menu...');
    
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (!menuToggle) {
        console.error('❌ menuToggle element not found!');
        console.log('Looking for #menuToggle in document:', document.getElementById('menuToggle'));
        return;
    }
    
    if (!mobileMenu) {
        console.error('❌ .mobile-menu element not found!');
        return;
    }
    
    console.log('✅ Found menu elements:', {
        toggle: menuToggle,
        menu: mobileMenu
    });
    
    // Remove any existing event listeners by cloning
    const newToggle = menuToggle.cloneNode(true);
    if (menuToggle.parentNode) {
        menuToggle.parentNode.replaceChild(newToggle, menuToggle);
    }
    
    // Re-get references after cloning
    const currentToggle = document.getElementById('menuToggle');
    const currentMenu = document.querySelector('.mobile-menu');
    
    if (!currentToggle || !currentMenu) {
        console.error('Failed to get elements after cloning');
        return;
    }
    
    // Add click event with proper error handling
    currentToggle.addEventListener('click', function(e) {
        console.log('📱 Mobile menu toggle clicked!');
        e.preventDefault();
        e.stopPropagation();
        
        // Toggle classes
        this.classList.toggle('active');
        currentMenu.classList.toggle('active');
        
        // Toggle body overflow
        if (currentMenu.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('menu-open');
            console.log('Menu opened');
        } else {
            document.body.style.overflow = '';
            document.body.classList.remove('menu-open');
            console.log('Menu closed');
        }
    });
    
    // Close menu when clicking links
    const mobileLinks = currentMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            currentToggle.classList.remove('active');
            currentMenu.classList.remove('active');
            document.body.style.overflow = '';
            document.body.classList.remove('menu-open');
            console.log('Menu closed (link clicked)');
        });
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!currentToggle.contains(e.target) && !currentMenu.contains(e.target)) {
            currentToggle.classList.remove('active');
            currentMenu.classList.remove('active');
            document.body.style.overflow = '';
            document.body.classList.remove('menu-open');
        }
    });
    
    mobileMenuInitialized = true;
    console.log('✅ Mobile menu initialized successfully');
    
    // Add visual indicator for debugging (remove in production)
    const debugIndicator = document.createElement('div');
    debugIndicator.style.cssText = `
        position: fixed;
        top: 10px;
        right: 10px;
        background: #4CAF50;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        z-index: 99999;
        font-size: 12px;
        font-family: monospace;
    `;
    debugIndicator.textContent = 'JS ✓';
    debugIndicator.id = 'jsDebugIndicator';
    document.body.appendChild(debugIndicator);
    
    setTimeout(() => {
        const indicator = document.getElementById('jsDebugIndicator');
        if (indicator) indicator.remove();
    }, 3000);
}

// Preloader
function initPreloader() {
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        setTimeout(() => {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        }, 1000);
    }
}

// Search Toggle
function initSearchToggle() {
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.querySelector('.search-bar');
    const searchClose = document.querySelector('.search-close');
    
    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', function() {
            searchBar.classList.add('active');
            const searchInput = searchBar.querySelector('input');
            if (searchInput) {
                searchInput.focus();
            }
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                searchBar.classList.remove('active');
            });
        }
        
        // Close search when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchBar.contains(event.target) && !searchToggle.contains(event.target)) {
                searchBar.classList.remove('active');
            }
        });
    }
}

// Back to Top Button
function initBackToTop() {
    const backToTop = document.getElementById('backToTop');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        });
    }
}

// Hero Slider (keep your existing slider code)
function initSlider() {
    const slider = document.querySelector('.hero-slider');
    if (!slider) return;
    
    const slides = slider.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dots .dot');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    function showSlide(index) {
        slides.forEach((slide) => slide.classList.remove('active'));
        dots.forEach((dot) => dot.classList.remove('active'));
        
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    }
    
    function nextSlide() {
        let nextIndex = currentSlide + 1;
        if (nextIndex >= totalSlides) nextIndex = 0;
        showSlide(nextIndex);
    }
    
    function prevSlide() {
        let prevIndex = currentSlide - 1;
        if (prevIndex < 0) prevIndex = totalSlides - 1;
        showSlide(prevIndex);
    }
    
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });
    
    // Auto slide
    let slideInterval = setInterval(nextSlide, 5000);
    slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
    slider.addEventListener('mouseleave', () => {
        slideInterval = setInterval(nextSlide, 5000);
    });
    
    showSlide(0);
}

// Rest of your functions (keep as is)
function initAddToCart() {
    document.addEventListener('click', function(event) {
        if (event.target.closest('.add-to-cart')) {
            const button = event.target.closest('.add-to-cart');
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productPrice = button.getAttribute('data-product-price');
            const imageUrl = button.getAttribute('data-image-url') || '';
            
            addToCart(productId, productName, productPrice, imageUrl);
        }
    });
}

function addToCart(productId, productName, productPrice, imageUrl, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('product_name', productName);
    formData.append('product_price', productPrice);
    formData.append('image_url', imageUrl);
    formData.append('quantity', quantity);
    
    fetch('includes/add-to-cart.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach((element) => {
        if (count > 0) {
            element.textContent = count;
            element.style.display = 'flex';
        } else {
            element.style.display = 'none';
        }
    });
}

function initWishlist() {
    document.addEventListener('click', function(event) {
        if (event.target.closest('.wishlist-btn')) {
            const button = event.target.closest('.wishlist-btn');
            const icon = button.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                showNotification('Added to wishlist', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                showNotification('Removed from wishlist', 'info');
            }
        }
    });
}

function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach((button) => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            const tabContent = document.getElementById(tabId);
            
            document.querySelectorAll('.tab-btn').forEach((btn) => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach((content) => content.classList.remove('active'));
            
            this.classList.add('active');
            if (tabContent) tabContent.classList.add('active');
        });
    });
}

function initQuantityControls() {
    document.addEventListener('click', function(event) {
        if (event.target.closest('.quantity-btn.minus')) {
            const button = event.target.closest('.quantity-btn.minus');
            const input = button.parentElement.querySelector('input[type="number"]');
            let value = parseInt(input.value);
            if (value > parseInt(input.min || 1)) {
                input.value = value - 1;
                const form = input.closest('form');
                if (form && form.classList.contains('quantity-form')) {
                    form.submit();
                }
            }
        }
        
        if (event.target.closest('.quantity-btn.plus')) {
            const button = event.target.closest('.quantity-btn.plus');
            const input = button.parentElement.querySelector('input[type="number"]');
            let value = parseInt(input.value);
            const max = parseInt(input.max || 999);
            if (value < max) {
                input.value = value + 1;
                const form = input.closest('form');
                if (form && form.classList.contains('quantity-form')) {
                    form.submit();
                }
            }
        }
    });
}

function showNotification(message, type = 'info') {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) existingNotification.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) notification.remove();
        }, 300);
    }, 3000);
    
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) notification.remove();
        }, 300);
    });
}
// Mobile improvements for shop page
document.addEventListener('DOMContentLoaded', function() {
    // Improve filter form on mobile
    const priceFilterForm = document.querySelector('.price-filter');
    if (priceFilterForm && window.innerWidth <= 768) {
        // Add mobile-friendly styling
        const inputs = priceFilterForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.setAttribute('inputmode', 'numeric');
            input.setAttribute('pattern', '[0-9]*');
        });
        
        // Add clear button functionality
        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'btn btn-sm btn-outline';
        clearBtn.textContent = 'Clear';
        clearBtn.style.marginLeft = '10px';
        clearBtn.addEventListener('click', function() {
            inputs.forEach(input => input.value = '');
            priceFilterForm.submit();
        });
        
        const submitBtn = priceFilterForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.parentNode.insertBefore(clearBtn, submitBtn.nextSibling);
        }
    }
    
    // Improve sort dropdown on mobile
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect && window.innerWidth <= 768) {
        sortSelect.style.minWidth = '150px';
        sortSelect.style.padding = '10px';
    }
    
    // Add touch feedback for product cards on mobile
    if ('ontouchstart' in window) {
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.style.cursor = 'pointer';
            
            // Add touch feedback
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
                this.style.transition = 'transform 0.1s';
            });
            
            card.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
});
// ===== Slider Navigation by Clicking Images =====
document.addEventListener('DOMContentLoaded', function() {
    // Get all slide images
    const slideImages = document.querySelectorAll('.slide-image');
    
    // Add click event to each image
    slideImages.forEach((image, index) => {
        image.addEventListener('click', function() {
            // Get current active slide
            const currentSlide = document.querySelector('.slide.active');
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            
            // Find current slide index
            let currentIndex = 0;
            slides.forEach((slide, i) => {
                if (slide === currentSlide) currentIndex = i;
            });
            
            // Calculate next slide index
            let nextIndex = (currentIndex + 1) % slides.length;
            
            // Remove active class from all
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Add active class to next
            slides[nextIndex].classList.add('active');
            dots[nextIndex].classList.add('active');
        });
        
        // Add cursor pointer to indicate clickable
        image.style.cursor = 'pointer';
    });
});
// Add notification styles
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
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
    
    .notification-warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        margin-left: 15px;
    }
`;
document.head.appendChild(notificationStyles);

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#') return;
        
        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            window.scrollTo({
                top: target.offsetTop - 80,
                behavior: 'smooth',
            });
        }
    });
});
// Simple dropdown functionality for customer panel
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close other dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                if (openMenu !== menu) {
                    openMenu.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            menu.classList.toggle('show');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    });
});
// Example: In your cart JavaScript
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA';
}
// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeAll);

// Also initialize if DOM is already loaded
if (document.readyState === 'interactive' || document.readyState === 'complete') {
    setTimeout(initializeAll, 100);
}
// Enhanced smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Skip if it's just "#"
            if (href === '#' || href === '#!') return;
            
            // Check if we're on the same page for anchor links
            if (href.startsWith('#')) {
                // Check if we're on index.php
                const currentPage = window.location.pathname;
                const isHomePage = currentPage.includes('index.php') || currentPage === '/' || currentPage === '/fashion-shop/' || currentPage === '/fashion-shop';
                
                if (isHomePage) {
                    // On home page, scroll to section
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                        
                        // Update URL without page reload
                        history.pushState(null, null, href);
                    }
                } else {
                    // Not on home page, go to home page with anchor
                    e.preventDefault();
                    const homeUrl = '<?php echo site_url("index.php"); ?>' + href;
                    window.location.href = homeUrl;
                }
            }
        });
    });
    
    // Highlight current section in navigation
    function highlightCurrentSection() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link[href^="#"], .mobile-nav a[href^="#"]');
        
        if (sections.length === 0 || navLinks.length === 0) return;
        
        const scrollPos = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                // Remove active class from all nav links
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });
                
                // Add active class to current section link
                const currentLink = document.querySelector(`.nav-link[href="#${sectionId}"], .mobile-nav a[href="#${sectionId}"]`);
                if (currentLink) {
                    currentLink.classList.add('active');
                }
            }
        });
    }
    
    // Run on scroll and load
    window.addEventListener('scroll', highlightCurrentSection);
    window.addEventListener('load', highlightCurrentSection);
    
    // Check URL hash on page load
    if (window.location.hash) {
        const hash = window.location.hash;
        const target = document.querySelector(hash);
        if (target) {
            setTimeout(() => {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }, 100);
        }
    }
});