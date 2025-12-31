<?php
require_once '../includes/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions (for non-AJAX requests)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $action = $_POST['action'] ?? '';
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    switch ($action) {
        case 'update':
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    if ($quantity > 0) {
                        $item['quantity'] = $quantity;
                    } else {
                        // Remove item if quantity is 0
                        unset($_SESSION['cart'][array_search($item, $_SESSION['cart'])]);
                        $_SESSION['cart'] = array_values($_SESSION['cart']);
                    }
                    break;
                }
            }
            break;
            
        case 'remove':
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    break;
                }
            }
            break;
            
        case 'clear':
            $_SESSION['cart'] = [];
            break;
    }
    
    // Redirect to prevent form resubmission
    header('Location: cart.php');
    exit();
}

require_once '../includes/header.php';
?>

<style>
/* ===== CART PAGE STYLES ===== */
:root {
    --cart-primary: #ff4d6d;
    --cart-primary-light: #fff0f2;
    --cart-success: #28a745;
    --cart-warning: #ffc107;
    --cart-danger: #dc3545;
    --cart-light: #f8f9fa;
    --cart-border: #eaeaea;
    --cart-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.cart-section {
    padding: 30px 0 60px;
    background: linear-gradient(135deg, #f5f7fa 0%, #f8f9fa 100%);
    min-height: 70vh;
}

.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    position: relative;
    padding-bottom: 15px;
}

.page-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--cart-primary);
    border-radius: 2px;
}

.page-subtitle {
    color: #666;
    margin-bottom: 30px;
    font-size: 1.1rem;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: var(--cart-shadow);
    margin: 40px 0;
}

.empty-cart i {
    color: #ddd;
    margin-bottom: 20px;
}

.empty-cart h3 {
    color: #333;
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.empty-cart p {
    color: #666;
    margin-bottom: 30px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Cart Layout */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
    margin-top: 30px;
}

/* Cart Items */
.cart-items {
    background: white;
    border-radius: 15px;
    box-shadow: var(--cart-shadow);
    overflow: hidden;
}

.cart-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
    padding: 20px;
    background: var(--cart-light);
    border-bottom: 1px solid var(--cart-border);
    font-weight: 600;
    color: #333;
}

.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
    padding: 20px;
    align-items: center;
    border-bottom: 1px solid var(--cart-border);
    transition: all 0.3s ease;
}

.cart-item:hover {
    background: var(--cart-primary-light);
}

.item-product {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-image {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-image img:hover {
    transform: scale(1.05);
}

.product-info {
    flex: 1;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    line-height: 1.4;
}

.product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: var(--cart-primary);
}

.product-category {
    font-size: 0.85rem;
    color: #888;
}

.item-price,
.item-total {
    font-weight: 600;
    color: #333;
}

/* Quantity Control */
.quantity-form {
    display: inline-block;
}

.quantity-control {
    display: flex;
    align-items: center;
    background: white;
    border: 1px solid var(--cart-border);
    border-radius: 8px;
    overflow: hidden;
    width: 120px;
}

.quantity-btn {
    width: 36px;
    height: 36px;
    background: white;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-btn:hover {
    background: var(--cart-light);
    color: var(--cart-primary);
}

.quantity-btn.minus {
    border-right: 1px solid var(--cart-border);
}

.quantity-btn.plus {
    border-left: 1px solid var(--cart-border);
}

.quantity-input {
    width: 48px;
    height: 36px;
    border: none;
    text-align: center;
    font-size: 1rem;
    font-weight: 500;
    color: #333;
    background: transparent;
}

.quantity-input:focus {
    outline: none;
}

/* Remove Button */
.remove-form {
    display: inline-block;
}

.remove-btn {
    width: 36px;
    height: 36px;
    background: white;
    border: 1px solid var(--cart-border);
    border-radius: 8px;
    color: #dc3545;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-btn:hover {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
}

/* Cart Actions */
.cart-actions {
    padding: 25px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--cart-light);
    border-top: 1px solid var(--cart-border);
}

.cart-actions .btn {
    padding: 12px 25px;
    font-weight: 500;
    border-radius: 8px;
}

.btn-outline {
    background: white;
    border: 2px solid var(--cart-border);
    color: #666;
}

.btn-outline:hover {
    border-color: var(--cart-primary);
    color: var(--cart-primary);
    background: white;
}

.btn-danger {
    background: #dc3545;
    border: 2px solid #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
    border-color: #c82333;
}

/* Cart Summary */
.cart-summary {
    background: white;
    border-radius: 15px;
    box-shadow: var(--cart-shadow);
    padding: 30px;
    height: fit-content;
    position: sticky;
    top: 30px;
}

.cart-summary h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--cart-light);
}

.summary-details {
    margin-bottom: 30px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--cart-border);
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    padding-top: 15px;
    border-top: 2px solid var(--cart-border);
}

/* Coupon Code */
.coupon-code {
    margin-bottom: 30px;
}

.coupon-code h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.coupon-form {
    display: flex;
    gap: 10px;
}

.coupon-form input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid var(--cart-border);
    border-radius: 8px;
    font-size: 0.95rem;
}

.coupon-form input:focus {
    outline: none;
    border-color: var(--cart-primary);
}

.coupon-form .btn-sm {
    padding: 12px 20px;
    font-size: 0.95rem;
}

/* Checkout Button */
.btn-block {
    display: block;
    width: 100%;
    text-align: center;
    padding: 15px;
    font-size: 1.1rem;
    font-weight: 600;
}

.btn-lg {
    padding: 15px 30px;
}

.btn-primary {
    background: var(--cart-primary);
    border: 2px solid var(--cart-primary);
    color: white;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #ff2d55;
    border-color: #ff2d55;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 109, 0.3);
}

/* Payment Methods */
.payment-methods {
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid var(--cart-border);
}

.payment-methods p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 15px;
}

.payment-icons {
    display: flex;
    gap: 15px;
    font-size: 1.5rem;
}

.payment-icons i {
    color: #666;
    transition: color 0.3s ease;
    cursor: pointer;
}

.payment-icons i:hover {
    color: var(--cart-primary);
}

/* Responsive Design */
@media (max-width: 992px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    
    .cart-summary {
        position: static;
        margin-top: 30px;
    }
}

@media (max-width: 768px) {
    .cart-section {
        padding: 20px 0 40px;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .cart-container {
        padding: 0 15px;
    }
    
    .cart-header {
        display: none;
    }
    
    .cart-item {
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px 15px;
        position: relative;
    }
    
    .item-product {
        flex-direction: column;
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 1px dashed var(--cart-border);
    }
    
    .product-image {
        width: 120px;
        height: 120px;
    }
    
    .item-price,
    .item-quantity,
    .item-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .item-price::before {
        content: 'Price:';
        font-weight: 600;
        color: #666;
    }
    
    .item-total::before {
        content: 'Total:';
        font-weight: 600;
        color: #666;
    }
    
    .quantity-control {
        width: 140px;
    }
    
    .item-remove {
        position: absolute;
        top: 20px;
        right: 15px;
    }
    
    .cart-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .cart-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
    }
    
    .cart-summary {
        padding: 20px;
    }
    
    .coupon-form {
        flex-direction: column;
    }
    
    .product-image {
        width: 100px;
        height: 100px;
    }
}

/* Animation for cart actions */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cart-item {
    animation: fadeIn 0.3s ease;
}

/* Loading state */
.quantity-btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

/* Success message */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #28a745;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<section class="cart-section">
    <div class="cart-container">
        <h1 class="page-title">Shopping Cart</h1>
        <p class="page-subtitle">Review your items and proceed to checkout</p>
        
        <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart fa-4x"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any items to your cart yet. Start shopping to add products.</p>
            <a href="<?php echo page_url('shop.php'); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
        <?php else: ?>
        <div class="cart-content">
            <div class="cart-items">
                <div class="cart-header">
                    <div class="header-product">Product</div>
                    <div class="header-price">Price</div>
                    <div class="header-quantity">Quantity</div>
                    <div class="header-total">Total</div>
                    <div class="header-remove"></div>
                </div>
                
                <?php 
                $subtotal = 0;
                foreach ($_SESSION['cart'] as $item): 
                    if (!isset($item['price']) || !isset($item['quantity'])) {
                        continue;
                    }
                    
                    $item_total = $item['price'] * $item['quantity'];
                    $subtotal += $item_total;
                    $image_url = get_product_image_url($item['image'] ?? '');
                ?>
                <div class="cart-item">
                    <div class="item-product">
                        <div class="product-image">
                            <img src="<?php echo $image_url; ?>" 
                                 alt="<?php echo htmlspecialchars($item['name'] ?? 'Product'); ?>"
                                 onerror="this.src='<?php echo asset_url('images/still-life-rendering-jackets-display.jpg'); ?>'">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="<?php echo page_url('product-detail.php?slug=' . urlencode($item['slug'] ?? '')); ?>">
                                    <?php echo htmlspecialchars($item['name'] ?? 'Product'); ?>
                                </a>
                            </h3>
                            <?php if (isset($item['category'])): ?>
                            <p class="product-category"><?php echo htmlspecialchars($item['category']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="item-price">
                        <span class="price"><?php echo format_price($item['price'] ?? 0); ?></span>
                    </div>
                    
                    <div class="item-quantity">
                        <form method="POST" class="quantity-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <div class="quantity-control">
                                <button type="button" class="quantity-btn minus" data-product-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" class="quantity-input" 
                                       value="<?php echo $item['quantity']; ?>" min="1" max="99"
                                       data-product-id="<?php echo $item['id']; ?>">
                                <button type="button" class="quantity-btn plus" data-product-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="item-total">
                        <span class="total-price"><?php echo format_price($item_total); ?></span>
                    </div>
                    
                    <div class="item-remove">
                        <form method="POST" class="remove-form">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="remove-btn" title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-actions">
                    <a href="<?php echo page_url('shop.php'); ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <form method="POST" class="clear-form">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear your cart?');">
                            <i class="fas fa-trash-alt"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?php echo format_price($subtotal); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span><?php echo format_price($subtotal * 0.05); // 5% tax ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <?php $total_with_tax = $subtotal + ($subtotal * 0.05); ?>
                        <span><?php echo format_price($total_with_tax); ?></span>
                    </div>
                </div>
                
                <div class="coupon-code">
                    <h4>Have a coupon code?</h4>
                    <form class="coupon-form" id="couponForm">
                        <input type="text" placeholder="Enter coupon code" id="couponInput">
                        <button type="button" class="btn btn-sm btn-primary" id="applyCoupon">Apply</button>
                    </form>
                    <div id="couponMessage" class="mt-2" style="display: none;"></div>
                </div>
                
                <a href="<?php echo page_url('checkout.php'); ?>" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>
                
                <div class="payment-methods">
                    <p>We accept:</p>
                    <div class="payment-icons">
                        <i class="fas fa-mobile-alt" title="MTN Mobile Money"></i>
                        <i class="fas fa-sim-card" title="Orange Money"></i>
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    // Quantity control
    $('.quantity-btn.plus').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const input = $(this).siblings('.quantity-input');
        let value = parseInt(input.val());
        
        if (value < 99) {
            input.val(value + 1);
            updateCartQuantity(productId, value + 1);
        }
    });
    
    $('.quantity-btn.minus').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const input = $(this).siblings('.quantity-input');
        let value = parseInt(input.val());
        
        if (value > 1) {
            input.val(value - 1);
            updateCartQuantity(productId, value - 1);
        }
    });
    
    $('.quantity-input').change(function() {
        const productId = $(this).data('product-id');
        let value = parseInt($(this).val());
        
        if (value < 1) value = 1;
        if (value > 99) value = 99;
        
        $(this).val(value);
        updateCartQuantity(productId, value);
    });
    
    function updateCartQuantity(productId, quantity) {
        const form = $(`.quantity-form input[name="product_id"][value="${productId}"]`).closest('form');
        const submitBtn = form.find('.quantity-btn');
        
        // Add loading state
        submitBtn.addClass('loading').prop('disabled', true);
        
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'update',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                // Reload page to update totals
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            },
            error: function() {
                alert('Error updating quantity. Please try again.');
                submitBtn.removeClass('loading').prop('disabled', false);
            }
        });
    }
    
    // Remove item with confirmation
    $('.remove-form').submit(function(e) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            e.preventDefault();
            return false;
        }
        
        // Show loading
        $(this).find('.remove-btn').html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
        return true;
    });
    
    // Clear cart with confirmation
    $('.clear-form').submit(function(e) {
        if (!confirm('Are you sure you want to clear your entire cart?')) {
            e.preventDefault();
            return false;
        }
        
        $(this).find('button').html('<i class="fas fa-spinner fa-spin"></i> Clearing...').prop('disabled', true);
        return true;
    });
    
    // Coupon code
    $('#applyCoupon').click(function() {
        const couponCode = $('#couponInput').val().trim();
        const messageDiv = $('#couponMessage');
        
        if (!couponCode) {
            showCouponMessage('Please enter a coupon code.', 'error');
            return;
        }
        
        $(this).html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
        
        // Simulate API call
        setTimeout(() => {
            if (couponCode.toUpperCase() === 'SUMMER30') {
                showCouponMessage('🎉 Coupon applied! You got 30% off your order.', 'success');
                $(this).html('Applied').prop('disabled', true);
                $('#couponInput').prop('disabled', true);
            } else {
                showCouponMessage('Invalid coupon code. Please try again.', 'error');
                $(this).html('Apply').prop('disabled', false);
            }
        }, 1000);
    });
    
    $('#couponInput').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#applyCoupon').click();
        }
    });
    
    function showCouponMessage(message, type) {
        const messageDiv = $('#couponMessage');
        messageDiv.removeClass('alert-success alert-danger')
                  .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                  .html(message)
                  .slideDown();
        
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.slideUp();
            }, 5000);
        }
    }
    
    // Update cart count in real-time
    function updateCartCount(count) {
        $('.cart-count').text(count);
        if (count === 0) {
            $('.cart-count').hide();
        } else {
            $('.cart-count').show();
        }
    }
    
    // Show animation when item is added/removed
    $('.cart-item').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});
</script>

<?php
require_once '../includes/footer.php';
?>