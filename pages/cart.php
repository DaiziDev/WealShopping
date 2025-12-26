<?php
require_once '../includes/config.php';

// Handle cart actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    switch ($action) {
        case 'update':
            $quantity = intval($_POST['quantity']);
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    if ($quantity > 0) {
                        $item['quantity'] = $quantity;
                    } else {
                        // Remove item if quantity is 0
                        unset($_SESSION['cart'][array_search($item, $_SESSION['cart'])]);
                    }
                    break;
                }
            }
            break;
            
        case 'remove':
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            break;
            
        case 'clear':
            $_SESSION['cart'] = [];
            break;
    }
    
    header('Location: cart.php');
    exit();
}

require_once '../includes/header.php';
?>

<section class="cart-section section-padding">
    <div class="container">
        <h1 class="page-title">Shopping Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart fa-3x"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
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
                    $item_total = $item['price'] * $item['quantity'];
                    $subtotal += $item_total;
                ?>
                <div class="cart-item" data-product-id="<?php echo $item['id']; ?>">
                    <div class="item-product">
                        <div class="product-image">
                            <img src="<?php echo $item['image'] ?: '../assets/images/still-life-rendering-jackets-display.jpg'; ?>" alt="<?php echo $item['name']; ?>">
                        </div>
                        <div class="product-info">
                            <h4 class="product-title"><?php echo $item['name']; ?></h4>
                        </div>
                    </div>
                    <div class="item-price">
                        <span class="price">$<?php echo number_format($item['price'], 2); ?></span>
                    </div>
                    <div class="item-quantity">
                        <form method="POST" class="quantity-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <div class="quantity-control">
                                <button type="button" class="quantity-btn minus">-</button>
                                <input type="number" name="quantity" class="quantity-input" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="button" class="quantity-btn plus">+</button>
                            </div>
                        </form>
                    </div>
                    <div class="item-total">
                        <span class="total-price">$<?php echo number_format($item_total, 2); ?></span>
                    </div>
                    <div class="item-remove">
                        <form method="POST" class="remove-form">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="remove-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-actions">
                    <a href="shop.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <form method="POST">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-outline btn-danger">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span>$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                </div>
                
                <div class="coupon-code">
                    <h4>Have a coupon?</h4>
                    <form class="coupon-form">
                        <input type="text" placeholder="Enter coupon code">
                        <button type="submit" class="btn btn-sm">Apply</button>
                    </form>
                </div>
                
                <a href="checkout.php" class="btn btn-primary btn-block btn-lg">
                    Proceed to Checkout
                </a>
                
                <div class="payment-methods">
                    <p>We accept:</p>
                    <div class="payment-icons">
                        <i class="fas fa-mobile-alt" title="MTN Mobile Money"></i>
                        <i class="fas fa-sim-card" title="Orange Money"></i>
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
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
    $('.quantity-btn.minus').click(function() {
        var input = $(this).siblings('.quantity-input');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
            input.closest('form').submit();
        }
    });
    
    $('.quantity-btn.plus').click(function() {
        var input = $(this).siblings('.quantity-input');
        var value = parseInt(input.val());
        input.val(value + 1);
        input.closest('form').submit();
    });
    
    // Auto-submit on quantity change
    $('.quantity-input').change(function() {
        $(this).closest('form').submit();
    });
});
</script>

<?php
require_once '../includes/footer.php';
?>