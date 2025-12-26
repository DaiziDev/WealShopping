// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            
            addToCart(productId, productName, productPrice);
        });
    });
    
    // Wishlist buttons
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            toggleWishlist(productId, this);
        });
    });
    
    // Search functionality
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.querySelector('.search-bar');
    const searchClose = document.querySelector('.search-close');
    
    if (searchToggle) {
        searchToggle.addEventListener('click', () => {
            searchBar.classList.toggle('active');
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', () => {
                searchBar.classList.remove('active');
            });
        }
    }
    
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
    }
});

// Cart functions
function addToCart(productId, productName, productPrice, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    fetch('includes/add-to-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cartCount);
            showNotification('Added to cart: ' + productName, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
    });
}

function toggleWishlist(productId, button) {
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

function showNotification(message, type = 'info') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
    
    // Close button
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    });
}

// Add notification styles
const style = document.createElement('style');
style.textContent = `
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
document.head.appendChild(style);