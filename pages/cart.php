<?php
$pageTitle = "Shopping Cart";
include_once '../includes/header.php';

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Sample cart items
$cartItems = $_SESSION['cart'];

// If cart is empty, show sample items for demonstration
if (empty($cartItems)) {
    $cartItems = [
        [
            'id' => 1,
            'name' => 'Premium Denim Jacket',
            'price' => 89.99,
            'original_price' => 120.00,
            'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
            'size' => 'M',
            'color' => 'Dark Blue',
            'quantity' => 1
        ],
        [
            'id' => 3,
            'name' => 'Leather Running Shoes',
            'price' => 129.99,
            'original_price' => null,
            'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
            'size' => '9',
            'color' => 'White',
            'quantity' => 2
        ],
        [
            'id' => 8,
            'name' => 'Sunglasses',
            'price' => 79.99,
            'original_price' => 99.99,
            'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
            'size' => 'One Size',
            'color' => 'Black',
            'quantity' => 1
        ]
    ];
}

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal > 100 ? 0 : 9.99;
$tax = $subtotal * 0.08; // 8% tax
$total = $subtotal + $shipping + $tax;
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li>Shopping Cart</li>
        </ul>
    </div>
</div>

<!-- Cart Section -->
<section class="cart section-padding">
    <div class="container">
        <h1 class="page-title">Shopping Cart</h1>
        
        <?php if (empty($cartItems)): ?>
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
        </div>
        <?php else: ?>
        <!-- Cart with Items -->
        <div class="cart-content">
            <div class="cart-items">
                <div class="cart-table-header">
                    <div class="header-product">Product</div>
                    <div class="header-price">Price</div>
                    <div class="header-quantity">Quantity</div>
                    <div class="header-total">Total</div>
                    <div class="header-actions">Actions</div>
                </div>
                
                <div class="cart-items-list">
                    <?php foreach ($cartItems as $index => $item): ?>
                    <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                        <div class="item-product">
                            <div class="product-image">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" loading="lazy">
                            </div>
                            <div class="product-details">
                                <h3 class="product-name"><?php echo $item['name']; ?></h3>
                                <div class="product-variants">
                                    <?php if (isset($item['size'])): ?>
                                    <span class="variant">Size: <?php echo $item['size']; ?></span>
                                    <?php endif; ?>
                                    <?php if (isset($item['color'])): ?>
                                    <span class="variant">Color: <?php echo $item['color']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="item-price">
                            <span class="current-price">$<?php echo number_format($item['price'], 2); ?></span>
                            <?php if ($item['original_price']): ?>
                            <span class="original-price">$<?php echo number_format($item['original_price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-quantity">
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn minus">-</button>
                                <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="10">
                                <button type="button" class="quantity-btn plus">+</button>
                            </div>
                        </div>
                        
                        <div class="item-total">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                        
                        <div class="item-actions">
                            <button class="action-btn remove-item" title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="action-btn move-to-wishlist" title="Move to wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-actions">
                    <a href="shop.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <button class="btn btn-outline" id="updateCart">
                        <i class="fas fa-sync-alt"></i> Update Cart
                    </button>
                    <button class="btn btn-outline" id="clearCart">
                        <i class="fas fa-trash"></i> Clear Cart
                    </button>
                </div>
            </div>
            
            <div class="cart-summary">
                <div class="summary-card">
                    <h3>Order Summary</h3>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="shipping-info">
                            <?php if ($shipping == 0): ?>
                            <span class="free-shipping">FREE</span>
                            <?php else: ?>
                            $<?php echo number_format($shipping, 2); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Tax (8%)</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="total-price">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <div class="promo-code">
                        <h4>Promo Code</h4>
                        <div class="promo-input">
                            <input type="text" placeholder="Enter promo code">
                            <button type="button" class="btn btn-sm">Apply</button>
                        </div>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-primary btn-lg checkout-btn">
                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                    </a>
                    
                    <div class="payment-methods-summary">
                        <p>We accept:</p>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fab fa-cc-apple-pay"></i>
                        </div>
                    </div>
                </div>
                
                <div class="shipping-info-card">
                    <h4><i class="fas fa-shipping-fast"></i> Free Shipping</h4>
                    <p>Free standard shipping on orders over $100</p>
                    
                    <h4><i class="fas fa-undo"></i> Easy Returns</h4>
                    <p>30-day return policy. No questions asked.</p>
                    
                    <h4><i class="fas fa-lock"></i> Secure Payment</h4>
                    <p>Your payment information is processed securely.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Recently Viewed -->
<section class="recently-viewed section-padding">
    <div class="container">
        <h2 class="section-title">Recently Viewed</h2>
        
        <div class="products-grid">
            <?php
            $recentProducts = [
                [
                    'id' => 5,
                    'name' => 'Casual T-Shirt',
                    'category' => 'Men\'s Clothing',
                    'price' => 29.99,
                    'original_price' => 39.99,
                    'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                    'badge' => 'Sale'
                ],
                [
                    'id' => 6,
                    'name' => 'Summer Dress',
                    'category' => 'Women\'s Clothing',
                    'price' => 59.99,
                    'original_price' => 79.99,
                    'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                    'badge' => 'New'
                ],
                [
                    'id' => 11,
                    'name' => 'Casual Sneakers',
                    'category' => 'Footwear',
                    'price' => 89.99,
                    'original_price' => 119.99,
                    'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                    'badge' => 'Sale'
                ],
                [
                    'id' => 12,
                    'name' => 'Leather Wallet',
                    'category' => 'Accessories',
                    'price' => 49.99,
                    'original_price' => null,
                    'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                    'badge' => null
                ]
            ];
            
            foreach ($recentProducts as $product) {
                $badgeClass = '';
                if ($product['badge'] == 'Sale') {
                    $badgeClass = 'sale';
                }
                
                echo '
                <div class="product-card">
                    <div class="product-image">
                        <img src="' . $product['image'] . '" alt="' . $product['name'] . '" loading="lazy">
                        ' . ($product['badge'] ? '<span class="product-badge ' . $badgeClass . '">' . $product['badge'] . '</span>' : '') . '
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><a href="product-detail.php?id=' . $product['id'] . '">' . $product['name'] . '</a></h3>
                        <p class="product-category">' . $product['category'] . '</p>
                        <div class="product-price">
                            <span class="current-price">$' . number_format($product['price'], 2) . '</span>
                            ' . (isset($product['original_price']) ? '<span class="original-price">$' . number_format($product['original_price'], 2) . '</span>' : '') . '
                        </div>
                        <button class="btn btn-sm add-to-cart">Add to Cart</button>
                    </div>
                </div>
                ';
            }
            ?>
        </div>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>

<!-- Cart Page CSS -->
<style>
/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: var(--spacing-xl) 0;
}

.empty-cart-icon {
    font-size: 5rem;
    color: var(--light-gray);
    margin-bottom: var(--spacing-md);
}

.empty-cart h2 {
    margin-bottom: var(--spacing-sm);
    color: var(--dark-color);
}

.empty-cart p {
    color: var(--gray-color);
    margin-bottom: var(--spacing-lg);
    font-size: 1.1rem;
}

/* Cart Content */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: var(--spacing-lg);
}

/* Cart Items */
.cart-items {
    background-color: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-md);
    box-shadow: var(--shadow-sm);
}

.cart-table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
    padding: 1rem;
    background-color: var(--light-color);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-sm);
    font-weight: 600;
    color: var(--dark-color);
}

.cart-items-list {
    display: grid;
    gap: var(--spacing-sm);
}

.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
    padding: 1rem;
    align-items: center;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-lg);
    transition: var(--transition-fast);
}

.cart-item:hover {
    border-color: var(--secondary-color);
    box-shadow: var(--shadow-sm);
}

.item-product {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.product-image {
    width: 100px;
    height: 100px;
    border-radius: var(--radius-md);
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.product-variants {
    display: flex;
    gap: var(--spacing-sm);
    font-size: 0.9rem;
    color: var(--gray-color);
}

.variant {
    padding: 0.25rem 0.75rem;
    background-color: var(--light-gray);
    border-radius: var(--radius-sm);
}

.item-price {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-price .current-price {
    font-weight: 600;
    color: var(--dark-color);
}

.item-price .original-price {
    font-size: 0.9rem;
    color: var(--gray-color);
    text-decoration: line-through;
}

.item-quantity .quantity-selector {
    display: flex;
    max-width: 120px;
    margin: 0 auto;
}

.item-quantity .quantity-btn {
    width: 30px;
    height: 30px;
    border: 1px solid var(--light-gray);
    background-color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.item-quantity .quantity-btn.minus {
    border-radius: var(--radius-sm) 0 0 var(--radius-sm);
}

.item-quantity .quantity-btn.plus {
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
}

.item-quantity .quantity-input {
    width: 40px;
    height: 30px;
    border: 1px solid var(--light-gray);
    border-left: none;
    border-right: none;
    text-align: center;
    font-family: var(--font-main);
    font-size: 0.9rem;
    color: var(--dark-color);
    background-color: white;
}

.item-total {
    font-weight: 700;
    color: var(--dark-color);
    font-size: 1.1rem;
}

.item-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-btn {
    width: 35px;
    height: 35px;
    border: 1px solid var(--light-gray);
    background-color: white;
    border-radius: var(--radius-sm);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-color);
    transition: var(--transition-fast);
}

.action-btn:hover {
    background-color: var(--light-gray);
    color: var(--dark-color);
}

.action-btn.remove-item:hover {
    background-color: #ffebee;
    color: #f44336;
    border-color: #f44336;
}

.action-btn.move-to-wishlist:hover {
    background-color: #fff8e1;
    color: #ff9800;
    border-color: #ff9800;
}

/* Cart Actions */
.cart-actions {
    display: flex;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--light-gray);
}

.cart-actions .btn {
    flex: 1;
}

/* Cart Summary */
.cart-summary {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.summary-card {
    background-color: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
}

.summary-card h3 {
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--light-gray);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--light-gray);
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark-color);
}

.total-price {
    color: var(--secondary-color);
}

.free-shipping {
    color: #4CAF50;
    font-weight: 600;
}

/* Promo Code */
.promo-code {
    margin: var(--spacing-md) 0;
}

.promo-code h4 {
    margin-bottom: var(--spacing-sm);
    font-size: 1rem;
}

.promo-input {
    display: flex;
    gap: 0.5rem;
}

.promo-input input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-sm);
    font-family: var(--font-main);
}

.promo-input .btn {
    padding: 0.75rem 1.5rem;
}

.checkout-btn {
    width: 100%;
    margin-top: var(--spacing-md);
    padding: 1rem;
    font-size: 1.1rem;
}

.payment-methods-summary {
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--light-gray);
    text-align: center;
}

.payment-methods-summary p {
    margin-bottom: var(--spacing-sm);
    color: var(--gray-color);
    font-size: 0.9rem;
}

.payment-icons {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
    font-size: 1.5rem;
    color: var(--gray-color);
}

/* Shipping Info Card */
.shipping-info-card {
    background-color: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
}

.shipping-info-card h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.shipping-info-card h4 i {
    color: var(--secondary-color);
}

.shipping-info-card p {
    margin-bottom: var(--spacing-md);
    font-size: 0.9rem;
    color: var(--gray-color);
}

.shipping-info-card h4:last-of-type {
    margin-bottom: 0;
}

.shipping-info-card p:last-child {
    margin-bottom: 0;
}

/* Recently Viewed */
.recently-viewed {
    background-color: var(--light-color);
}

.recently-viewed .section-title {
    text-align: center;
}

.recently-viewed .products-grid {
    grid-template-columns: repeat(4, 1fr);
}

/* Responsive */
@media (max-width: 1200px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    
    .recently-viewed .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .cart-table-header,
    .cart-item {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }
    
    .cart-table-header .header-price,
    .cart-table-header .header-quantity,
    .cart-table-header .header-total,
    .cart-table-header .header-actions {
        display: none;
    }
    
    .cart-item .item-price,
    .cart-item .item-quantity,
    .cart-item .item-total,
    .cart-item .item-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-top: 1px solid var(--light-gray);
    }
    
    .cart-item .item-actions {
        justify-content: flex-start;
        gap: var(--spacing-sm);
    }
    
    .recently-viewed .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .cart-actions {
        flex-direction: column;
    }
    
    .product-image {
        width: 80px;
        height: 80px;
    }
    
    .recently-viewed .products-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .item-product {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
    }
    
    .promo-input {
        flex-direction: column;
    }
}
</style>

<!-- Cart Page JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update quantity
    const quantityMinusBtns = document.querySelectorAll('.quantity-btn.minus');
    const quantityPlusBtns = document.querySelectorAll('.quantity-btn.plus');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityMinusBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            let value = parseInt(quantityInputs[index].value);
            if (value > 1) {
                quantityInputs[index].value = value - 1;
                updateItemTotal(index);
            }
        });
    });
    
    quantityPlusBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            let value = parseInt(quantityInputs[index].value);
            if (value < 10) {
                quantityInputs[index].value = value + 1;
                updateItemTotal(index);
            }
        });
    });
    
    quantityInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 10) this.value = 10;
            updateItemTotal(index);
        });
    });
    
    function updateItemTotal(index) {
        const cartItem = document.querySelectorAll('.cart-item')[index];
        const price = parseFloat(cartItem.querySelector('.current-price').textContent.replace('$', ''));
        const quantity = parseInt(quantityInputs[index].value);
        const totalElement = cartItem.querySelector('.item-total');
        
        totalElement.textContent = '$' + (price * quantity).toFixed(2);
        updateCartTotals();
    }
    
    // Remove item
    const removeBtns = document.querySelectorAll('.remove-item');
    removeBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                cartItem.style.transform = 'translateX(-100%)';
                cartItem.style.opacity = '0';
                
                setTimeout(() => {
                    cartItem.remove();
                    updateCartTotals();
                    
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        let currentCount = parseInt(cartCount.textContent);
                        cartCount.textContent = Math.max(0, currentCount - 1);
                    }
                    
                    // Check if cart is empty
                    const cartItems = document.querySelectorAll('.cart-item');
                    if (cartItems.length === 0) {
                        location.reload(); // Reload to show empty cart message
                    }
                }, 300);
            }
        });
    });
    
    // Move to wishlist
    const wishlistBtns = document.querySelectorAll('.move-to-wishlist');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                alert('Item moved to wishlist!');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                alert('Item removed from wishlist!');
            }
        });
    });
    
    // Update cart
    const updateCartBtn = document.getElementById('updateCart');
    if (updateCartBtn) {
        updateCartBtn.addEventListener('click', function() {
            // In a real app, this would send updated quantities to server
            alert('Cart updated successfully!');
        });
    }
    
    // Clear cart
    const clearCartBtn = document.getElementById('clearCart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                // In a real app, this would clear cart on server
                const cartItems = document.querySelectorAll('.cart-item');
                cartItems.forEach(item => {
                    item.style.transform = 'translateX(-100%)';
                    item.style.opacity = '0';
                });
                
                setTimeout(() => {
                    location.reload(); // Reload to show empty cart
                }, 500);
            }
        });
    }
    
    // Apply promo code
    const applyPromoBtn = document.querySelector('.promo-input .btn');
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', function() {
            const promoInput = this.previousElementSibling;
            const promoCode = promoInput.value.trim();
            
            if (promoCode === '') {
                alert('Please enter a promo code.');
                return;
            }
            
            // In a real app, this would validate the promo code with server
            if (promoCode === 'SUMMER30') {
                alert('Promo code applied! You got 30% off!');
                promoInput.value = '';
            } else {
                alert('Invalid promo code. Please try again.');
            }
        });
    }
    
    // Update cart totals
    function updateCartTotals() {
        const cartItems = document.querySelectorAll('.cart-item');
        let subtotal = 0;
        
        cartItems.forEach(item => {
            const price = parseFloat(item.querySelector('.current-price').textContent.replace('$', ''));
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            subtotal += price * quantity;
        });
        
        const shipping = subtotal > 100 ? 0 : 9.99;
        const tax = subtotal * 0.08;
        const total = subtotal + shipping + tax;
        
        // Update summary
        document.querySelector('.summary-row:nth-child(1) span:last-child').textContent = '$' + subtotal.toFixed(2);
        document.querySelector('.summary-row:nth-child(2) .shipping-info').innerHTML = shipping === 0 ? 
            '<span class="free-shipping">FREE</span>' : 
            '$' + shipping.toFixed(2);
        document.querySelector('.summary-row:nth-child(3) span:last-child').textContent = '$' + tax.toFixed(2);
        document.querySelector('.total-price').textContent = '$' + total.toFixed(2);
    }
    
    // Add to cart from recently viewed
    const addToCartBtns = document.querySelectorAll('.recently-viewed .add-to-cart');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update cart count
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                let currentCount = parseInt(cartCount.textContent);
                cartCount.textContent = currentCount + 1;
                
                // Animation
                cartCount.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    cartCount.style.transform = 'scale(1)';
                }, 300);
            }
            
            // Show success message
            alert('Item added to cart!');
        });
    });
});
</script>