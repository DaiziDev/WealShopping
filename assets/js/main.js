// Main functionality for the e-commerce site

// Product data (will be replaced with PHP/MySQL later)
const products = [
    {
        id: 1,
        name: "Premium Denim Jacket",
        category: "Men's Clothing",
        price: 89.99,
        originalPrice: 120.00,
        image: "assets/images/products/jacket.jpg",
        badge: "New",
        inStock: true
    },
    {
        id: 2,
        name: "Elegant Evening Dress",
        category: "Women's Clothing",
        price: 149.99,
        originalPrice: 199.99,
        image: "assets/images/products/dress.jpg",
        badge: "Sale",
        inStock: true
    },
    {
        id: 3,
        name: "Leather Running Shoes",
        category: "Footwear",
        price: 129.99,
        originalPrice: null,
        image: "assets/images/products/shoes.jpg",
        badge: null,
        inStock: true
    },
    {
        id: 4,
        name: "Designer Handbag",
        category: "Accessories",
        price: 199.99,
        originalPrice: null,
        image: "assets/images/products/handbag.jpg",
        badge: null,
        inStock: true
    }
];

// Cart functionality
class ShoppingCart {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('cart')) || [];
        this.updateCartCount();
    }
    
    addItem(product, quantity = 1) {
        const existingItem = this.items.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.items.push({
                ...product,
                quantity: quantity
            });
        }
        
        this.saveToLocalStorage();
        this.updateCartCount();
        return this.items;
    }
    
    removeItem(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        this.saveToLocalStorage();
        this.updateCartCount();
        return this.items;
    }
    
    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id === productId);
        if (item) {
            item.quantity = quantity;
            if (item.quantity <= 0) {
                this.removeItem(productId);
            }
        }
        this.saveToLocalStorage();
        this.updateCartCount();
        return this.items;
    }
    
    getTotalItems() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    }
    
    getTotalPrice() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    }
    
    clearCart() {
        this.items = [];
        this.saveToLocalStorage();
        this.updateCartCount();
    }
    
    saveToLocalStorage() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    }
    
    updateCartCount() {
        const cartCountElements = document.querySelectorAll('.cart-count');
        const totalItems = this.getTotalItems();
        
        cartCountElements.forEach(element => {
            element.textContent = totalItems;
            if (totalItems > 0) {
                element.style.display = 'flex';
            } else {
                element.style.display = 'none';
            }
        });
    }
}

// Initialize cart
const cart = new ShoppingCart();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize product grid if on shop page
    if (document.querySelector('.products-grid')) {
        renderFeaturedProducts();
    }
    
    // Add to cart functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart') || e.target.closest('.add-to-cart')) {
            const button = e.target.classList.contains('add-to-cart') ? e.target : e.target.closest('.add-to-cart');
            const productCard = button.closest('.product-card');
            const productId = parseInt(productCard.dataset.productId) || 1;
            
            // Find product data
            const product = products.find(p => p.id === productId) || products[0];
            
            // Add to cart
            cart.addItem(product);
            
            // Visual feedback
            button.textContent = 'Added!';
            button.style.backgroundColor = '#4CAF50';
            
            setTimeout(() => {
                button.textContent = 'Add to Cart';
                button.style.backgroundColor = '';
            }, 2000);
        }
    });
});

// Render featured products
function renderFeaturedProducts() {
    const productsGrid = document.querySelector('.products-grid');
    
    if (!productsGrid) return;
    
    // Clear existing content (except the first 4 placeholder cards if they exist)
    const placeholderCards = productsGrid.querySelectorAll('.product-card');
    if (placeholderCards.length >= 4) {
        // We'll replace the placeholder cards with dynamic ones
        productsGrid.innerHTML = '';
    }
    
    // Create product cards
    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card fade-in';
        productCard.dataset.productId = product.id;
        
        let badgeHTML = '';
        if (product.badge) {
            badgeHTML = `<span class="product-badge ${product.badge === 'Sale' ? 'sale' : ''}">${product.badge}</span>`;
        }
        
        let priceHTML = `<span class="current-price">$${product.price.toFixed(2)}</span>`;
        if (product.originalPrice) {
            priceHTML += `<span class="original-price">$${product.originalPrice.toFixed(2)}</span>`;
        }
        
        productCard.innerHTML = `
            <div class="product-image" style="background-color: #f5f5f5;">
                ${badgeHTML}
                <button class="wishlist-btn"><i class="far fa-heart"></i></button>
            </div>
            <div class="product-info">
                <h3 class="product-title">${product.name}</h3>
                <p class="product-category">${product.category}</p>
                <div class="product-price">
                    ${priceHTML}
                </div>
                <button class="btn btn-sm add-to-cart">Add to Cart</button>
            </div>
        `;
        
        productsGrid.appendChild(productCard);
    });
}

// Image lazy loading
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
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
    
    lazyImages.forEach(img => imageObserver.observe(img));
}

// Product filtering and sorting (for shop page)
function initProductFiltering() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const sortSelect = document.querySelector('.sort-select');
    
    if (filterButtons.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Filter products (will be implemented with PHP/MySQL)
                const filter = this.dataset.filter;
                filterProducts(filter);
            });
        });
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            sortProducts(sortBy);
        });
    }
}

function filterProducts(filter) {
    console.log(`Filtering by: ${filter}`);
    // Will be implemented with PHP/MySQL
}

function sortProducts(sortBy) {
    console.log(`Sorting by: ${sortBy}`);
    // Will be implemented with PHP/MySQL
}