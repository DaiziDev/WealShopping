<?php
require_once '../includes/config.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

if (!isLoggedIn()) {
    $_SESSION['redirect_to'] = 'checkout.php';
    header('Location: login.php');
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle order placement
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    $required = ['customer_name', 'customer_email', 'customer_phone', 'shipping_address', 'city', 'payment_method'];
    
    $missing = [];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $missing[] = $field;
        }
    }
    
    if (!empty($missing)) {
        $error = 'Please fill all required fields: ' . implode(', ', $missing);
    } else {
        try {
            $pdo->beginTransaction();
            
            // Generate order number
            $order_number = generateOrderNumber();
            
            // Calculate totals
            $subtotal = getCartTotal();
            $shipping_cost = 0; // Free shipping for now
            $tax_amount = 0; // No tax for now
            $total_amount = $subtotal + $shipping_cost + $tax_amount;
            
            // Insert order
            $sql = "INSERT INTO orders (
                order_number, user_id, customer_email, customer_name, customer_phone,
                shipping_address, billing_address, city, state, zip_code, country,
                subtotal, shipping_cost, tax_amount, total_amount,
                payment_method, payment_status, order_status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $order_number,
                $user_id,
                sanitize($_POST['customer_email']),
                sanitize($_POST['customer_name']),
                sanitize($_POST['customer_phone']),
                sanitize($_POST['shipping_address']),
                sanitize($_POST['billing_address']) ?: sanitize($_POST['shipping_address']),
                sanitize($_POST['city']),
                sanitize($_POST['state']),
                sanitize($_POST['zip_code']),
                sanitize($_POST['country']) ?: 'Cameroon',
                $subtotal,
                $shipping_cost,
                $tax_amount,
                $total_amount,
                sanitize($_POST['payment_method'])
            ]);
            
            $order_id = $pdo->lastInsertId();
            
            // Insert order items
            $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, total_price, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $pdo->prepare($sql);
            
            foreach ($_SESSION['cart'] as $item) {
                $item_total = $item['price'] * $item['quantity'];
                $stmt->execute([
                    $order_id,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item_total
                ]);
                
                // Update product quantity
                $update_sql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([$item['quantity'], $item['id']]);
            }
            
            $pdo->commit();
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            // Show success message with payment instructions
            $success = "Order placed successfully! Your order number is: <strong>$order_number</strong>";
            
            // Payment instructions based on selected method
            if ($_POST['payment_method'] == 'mtn_mobile_money') {
                $payment_instructions = "Please send <strong>$" . number_format($total_amount, 2) . "</strong> to MTN Mobile Money number: <strong>" . MTN_MOBILE_MONEY . "</strong> with your order number as reference.";
            } else {
                $payment_instructions = "Please send <strong>$" . number_format($total_amount, 2) . "</strong> to Orange Money number: <strong>" . ORANGE_MONEY . "</strong> with your order number as reference.";
            }
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Order failed: " . $e->getMessage();
        }
    }
}

require_once '../includes/header.php';

// Calculate cart totals
$subtotal = getCartTotal();
$shipping_cost = 0;
$tax_amount = 0;
$total_amount = $subtotal + $shipping_cost + $tax_amount;
?>

<section class="checkout-section section-padding">
    <div class="container">
        <h1 class="page-title">Checkout</h1>
        
        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="order-success">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Thank You for Your Order!</h2>
            <p><?php echo $success; ?></p>
            <p><?php echo $payment_instructions; ?></p>
            <p>Once payment is confirmed, we'll process your order and send you a confirmation email.</p>
            <div class="success-actions">
                <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
                <a href="account.php?tab=orders" class="btn btn-outline">View My Orders</a>
            </div>
        </div>
        <?php else: ?>
        <div class="checkout-content">
            <form method="POST" class="checkout-form">
                <div class="checkout-columns">
                    <!-- Billing Details -->
                    <div class="billing-details">
                        <h2>Billing & Shipping Details</h2>
                        
                        <div class="form-group">
                            <label for="customer_name">Full Name *</label>
                            <input type="text" id="customer_name" name="customer_name" 
                                   value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>" 
                                   class="form-control" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="customer_email">Email Address *</label>
                                    <input type="email" id="customer_email" name="customer_email" 
                                           value="<?php echo $user['email']; ?>" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="customer_phone">Phone Number *</label>
                                    <input type="tel" id="customer_phone" name="customer_phone" 
                                           value="<?php echo $user['phone']; ?>" 
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address *</label>
                            <textarea id="shipping_address" name="shipping_address" 
                                      class="form-control" rows="3" required><?php echo $user['address']; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="billing_address">Billing Address (if different)</label>
                            <textarea id="billing_address" name="billing_address" 
                                      class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="city">City *</label>
                                    <input type="text" id="city" name="city" 
                                           value="<?php echo $user['city']; ?>" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="state">State/Region</label>
                                    <input type="text" id="state" name="state" 
                                           value="<?php echo $user['state']; ?>" 
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="zip_code">ZIP/Postal Code</label>
                                    <input type="text" id="zip_code" name="zip_code" 
                                           value="<?php echo $user['zip_code']; ?>" 
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <select id="country" name="country" class="form-control">
                                        <option value="Cameroon" selected>Cameroon</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="order_notes">Order Notes (optional)</label>
                            <textarea id="order_notes" name="order_notes" 
                                      class="form-control" rows="3" 
                                      placeholder="Notes about your order, e.g., special delivery instructions"></textarea>
                        </div>
                    </div>
                    
                    <!-- Order Summary & Payment -->
                    <div class="order-summary">
                        <h2>Your Order</h2>
                        
                        <div class="order-review">
                            <div class="order-items">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="order-item">
                                    <span class="item-name"><?php echo $item['name']; ?> × <?php echo $item['quantity']; ?></span>
                                    <span class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-totals">
                                <div class="total-row">
                                    <span>Subtotal</span>
                                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="total-row">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div class="total-row">
                                    <span>Tax</span>
                                    <span>$0.00</span>
                                </div>
                                <div class="total-row grand-total">
                                    <span>Total</span>
                                    <span>$<?php echo number_format($total_amount, 2); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-methods">
                            <h3>Payment Method</h3>
                            <div class="payment-options">
                                <div class="payment-option">
                                    <input type="radio" id="mtn_mobile_money" name="payment_method" value="mtn_mobile_money" checked required>
                                    <label for="mtn_mobile_money">
                                        <i class="fas fa-mobile-alt"></i>
                                        <span>MTN Mobile Money</span>
                                    </label>
                                </div>
                                
                                <div class="payment-option">
                                    <input type="radio" id="orange_money" name="payment_method" value="orange_money" required>
                                    <label for="orange_money">
                                        <i class="fas fa-sim-card"></i>
                                        <span>Orange Money</span>
                                    </label>
                                </div>
                                
                                <div class="payment-option">
                                    <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery" required>
                                    <label for="cash_on_delivery">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>Cash on Delivery</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-instructions">
                                <p><strong>Note:</strong> After placing your order, you'll receive payment instructions for the selected method.</p>
                            </div>
                        </div>
                        
                        <div class="terms-agreement">
                            <div class="form-check">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a> *</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Place Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>