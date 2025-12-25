// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Preloader
    const preloader = document.querySelector('.preloader');
    window.addEventListener('load', function() {
        setTimeout(function() {
            preloader.classList.add('hidden');
        }, 1000);
    });
    
    // Mobile Menu Toggle
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            this.classList.toggle('active');
            
            // Toggle body scroll
            if (mobileMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        
        // Close mobile menu when clicking on a link
        const mobileLinks = document.querySelectorAll('.mobile-nav a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
    
    // Search Toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.querySelector('.search-bar');
    const searchClose = document.querySelector('.search-close');
    
    if (searchToggle) {
        searchToggle.addEventListener('click', function() {
            searchBar.classList.toggle('active');
            if (searchBar.classList.contains('active')) {
                searchBar.querySelector('input').focus();
            }
        });
    }
    
    if (searchClose) {
        searchClose.addEventListener('click', function() {
            searchBar.classList.remove('active');
        });
    }
    
    // Close search when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchBar.contains(e.target) && !searchToggle.contains(e.target)) {
            searchBar.classList.remove('active');
        }
    });
    
    // Hero Slider
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    let currentSlide = 0;
    let slideInterval;
    
    function showSlide(n) {
        // Reset all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        dots.forEach(dot => {
            dot.classList.remove('active');
        });
        
        // Handle slide index boundaries
        if (n >= slides.length) currentSlide = 0;
        if (n < 0) currentSlide = slides.length - 1;
        
        // Show current slide
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }
    
    function nextSlide() {
        currentSlide++;
        showSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide--;
        showSlide(currentSlide);
    }
    
    // Initialize slider
    if (slides.length > 0) {
        showSlide(currentSlide);
        
        // Auto slide every 5 seconds
        slideInterval = setInterval(nextSlide, 5000);
        
        // Next/Previous buttons
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                clearInterval(slideInterval);
                nextSlide();
                slideInterval = setInterval(nextSlide, 5000);
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                clearInterval(slideInterval);
                prevSlide();
                slideInterval = setInterval(nextSlide, 5000);
            });
        }
        
        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                clearInterval(slideInterval);
                currentSlide = index;
                showSlide(currentSlide);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
        
        // Pause on hover
        const heroSlider = document.querySelector('.hero-slider');
        if (heroSlider) {
            heroSlider.addEventListener('mouseenter', function() {
                clearInterval(slideInterval);
            });
            
            heroSlider.addEventListener('mouseleave', function() {
                slideInterval = setInterval(nextSlide, 5000);
            });
        }
    }
    
    // Scroll Animations
    function revealOnScroll() {
        const reveals = document.querySelectorAll('.reveal');
        
        reveals.forEach(element => {
            const windowHeight = window.innerHeight;
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < windowHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    }
    
    // Initialize scroll animations
    window.addEventListener('scroll', revealOnScroll);
    // Run once on load
    revealOnScroll();
    
    // Wishlist Toggle
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            this.classList.toggle('active');
            
            if (this.classList.contains('active')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                
                // Animation effect
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
        });
    });
    
    // Add to Cart Animation
    const addToCartBtns = document.querySelectorAll('.add-to-cart');
    const cartCount = document.querySelector('.cart-count');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update cart count
            let currentCount = parseInt(cartCount.textContent);
            cartCount.textContent = currentCount + 1;
            
            // Animation
            cartCount.style.transform = 'scale(1.5)';
            setTimeout(() => {
                cartCount.style.transform = 'scale(1)';
            }, 300);
            
            // Button feedback
            const originalText = this.textContent;
            this.textContent = 'Added!';
            this.style.backgroundColor = '#4CAF50';
            
            setTimeout(() => {
                this.textContent = originalText;
                this.style.backgroundColor = '';
            }, 2000);
        });
    });
    
    // Newsletter Form Submission
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button');
            
            // Simple validation
            if (emailInput.value && emailInput.value.includes('@')) {
                // Show success state
                submitBtn.innerHTML = '<i class="fas fa-check"></i>';
                submitBtn.style.backgroundColor = '#4CAF50';
                
                // Reset after 2 seconds
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                    submitBtn.style.backgroundColor = '';
                    emailInput.value = '';
                    alert('Thank you for subscribing to our newsletter!');
                }, 2000);
            } else {
                emailInput.style.borderColor = 'var(--secondary-color)';
                setTimeout(() => {
                    emailInput.style.borderColor = '';
                }, 2000);
            }
        });
    }
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        const backToTop = document.getElementById('backToTop');
        
        if (window.scrollY > 100) {
            navbar.style.padding = '0.5rem 0';
            navbar.style.boxShadow = 'var(--shadow-md)';
        } else {
            navbar.style.padding = '1rem 0';
            navbar.style.boxShadow = 'var(--shadow-sm)';
        }
        
        // Back to top button
        if (window.scrollY > 500) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });
    
    // Back to top button
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Lazy loading images
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    // Initialize lazy loading
    lazyLoadImages();
});