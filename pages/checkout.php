<?php
$pageTitle = "Checkout";
include_once '../includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li>Checkout</li>
        </ul>
    </div>
</div>

<!-- Checkout Section -->
<section class="checkout section-padding">
    <div class="container">
        <h1 class="page-title">Checkout</h1>
        
        <div class="checkout-progress">
            <div class="progress-step active">
                <div class="step-number">1</div>
                <div class="step-label">Shipping</div>
            </div>
            <div class="progress-step">
                <div class="step-number">2</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
        
        <div class="checkout-content">
            <!-- Checkout Form -->
            <div class="checkout-form">
                <form id="checkoutForm">
                    <!-- Step 1: Shipping Information -->
                    <div class="checkout-step active" id="step1">
                        <h2>Shipping Information</h2>
                        
                        <div class="form-group two-columns">
                            <div class="form-field">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-field">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-field">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-field">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-field">
                                <label for="address">Street Address *</label>
                                <input type="text" id="address" name="address" required>
                            </div>
                        </div>
                        
                        <div class="form-group two-columns">
                            <div class="form-field">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                            <div class="form-field">
                                <label for="state">State/Province *</label>
                                <select id="state" name="state" required>
                                    <option value="">Select State</option>
                                    <option value="NY">New York</option>
                                    <option value="CA">California</option>
                                    <option value="TX">Texas</option>
                                    <option value="FL">Florida</option>
                                    <option value="IL">Illinois</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group two-columns">
                            <div class="form-field">
                                <label for="zipCode">ZIP/Postal Code *</label>
                                <input type="text" id="zipCode" name="zipCode" required>
                            </div>
                            <div class="form-field">
                                <label for="country">Country *</label>
                                <select id="country" name="country" required>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="AU">Australia</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-field">
                                <label for="shippingMethod">Shipping Method *</label>
                                <div class="shipping-options">
                                    <div class="shipping-option">
                                        <input type="radio" id="standard" name="shippingMethod" value="standard" checked>
                                        <label for="standard">
                                            <span class="option-name">Standard Shipping</span>
                                            <span class="option-desc">5-7 business days</span>
                                            <span class="option-price">$9.99</span>
                                        </label>
                                    </div>
                                    <div class="shipping-option">
                                        <input type="radio" id="express" name="shippingMethod" value="express">
                                        <label for="express">
                                            <span class="option-name">Express Shipping</span>
                                            <span class="option-desc">2-3 business days</span>
                                            <span class="option-price">$19.99</span>
                                        </label>
                                    </div>
                                    <div class="shipping-option">
                                        <input type="radio" id="overnight" name="shippingMethod" value="overnight">
                                        <label for="overnight">
                                            <span class="option-name">Overnight Shipping</span>
                                            <span class="option-desc">Next business day</span>
                                            <span class="option-price">$29.99</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-field">
                                <label for="orderNotes">Order Notes (Optional)</label>
                                <textarea id="orderNotes" name="orderNotes" rows="4" placeholder="Special instructions for your order..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Payment Information -->
                    <div class="checkout-step" id="step2">
                        <h2>Payment Information</h2>
                        
                        <div class="payment-method-selector">
                            <div class="payment-method">
                                <input type="radio" id="creditCard" name="paymentMethod" value="creditCard" checked>
                                <label for="creditCard">
                                    <i class="far fa-credit-card"></i>
                                    <span>Credit/Debit Card</span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="paypal" name="paymentMethod" value="paypal">
                                <label for="paypal">
                                    <i class="fab fa-paypal"></i>
                                    <span>PayPal</span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="applePay" name="paymentMethod" value="applePay">
                                <label for="applePay">
                                    <i class="fab fa-apple-pay"></i>
                                    <span>Apple Pay</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Credit Card Form -->
                        <div class="payment-form credit-card-form active">
                            <div class="form-group">
                                <div class="form-field">
                                    <label for="cardNumber">Card Number *</label>
                                    <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                                    <div class="card-icons">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard"></i>
                                        <i class="fab fa-cc-amex"></i>
                                        <i class="fab fa-cc-discover"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group two-columns">
                                <div class="form-field">
                                    <label for="expiryDate">Expiry Date *</label>
                                    <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-field">
                                    <label for="cvv">CVV *</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4">
                                    <span class="cvv-info" title="3-digit security code on back of card">?</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-field">
                                    <label for="cardName">Name on Card *</label>
                                    <input type="text" id="cardName" name="cardName">
                                </div>
                            </div>
                        </div>
                        
                        <!-- PayPal Form -->
                        <div class="payment-form paypal-form">
                            <div class="paypal-info">
                                <p>You will be redirected to PayPal to complete your payment securely.</p>
                                <button type="button" class="btn btn-paypal">
                                    <i class="fab fa-paypal"></i> Proceed to PayPal
                                </button>
                            </div>
                        </div>
                        
                        <!-- Apple Pay Form -->
                        <div class="payment-form apple-pay-form">
                            <div class="apple-pay-info">
                                <p>Click the Apple Pay button to complete your purchase securely.</p>
                                <button type="button" class="btn btn-apple-pay">
                                    <i class="fab fa-apple-pay"></i> Pay with Apple Pay
                                </button>
                            </div>
                        </div>
                        
                        <div class="billing-address">
                            <h3>Billing Address</h3>
                            <div class="form-group">
                                <div class="form-field checkbox-field">
                                    <input type="checkbox" id="sameAsShipping" name="sameAsShipping" checked>
                                    <label for="sameAsShipping">Same as shipping address</label>
                                </div>
                            </div>
                            
                            <div class="billing-form">
                                <!-- Billing address fields would go here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Order Review -->
                    <div class="checkout-step" id="step3">
                        <h2>Order Review</h2>
                        
                        <div class="order-summary">
                            <div class="order-items">
                                <h3>Order Items</h3>
                                <div class="order-items-list">
                                    <div class="order-item">
                                        <div class="item-info">
                                            <div class="item-image">
                                                <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Denim Jacket" loading="lazy">
                                            </div>
                                            <div class="item-details">
                                                <h4>Premium Denim Jacket</h4>
                                                <p>Size: M, Color: Dark Blue</p>
                                            </div>
                                        </div>
                                        <div class="item-quantity">1</div>
                                        <div class="item-price">$89.99</div>
                                    </div>
                                    <div class="order-item">
                                        <div class="item-info">
                                            <div class="item-image">
                                                <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Running Shoes" loading="lazy">
                                            </div>
                                            <div class="item-details">
                                                <h4>Leather Running Shoes</h4>
                                                <p>Size: 9, Color: White</p>
                                            </div>
                                        </div>
                                        <div class="item-quantity">2</div>
                                        <div class="item-price">$259.98</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="order-totals">
                                <h3>Order Summary</h3>
                                <div class="totals-row">
                                    <span>Subtotal</span>
                                    <span>$349.97</span>
                                </div>
                                <div class="totals-row">
                                    <span>Shipping</span>
                                    <span>$9.99</span>
                                </div>
                                <div class="totals-row">
                                    <span>Tax</span>
                                    <span>$28.00</span>
                                </div>
                                <div class="totals-row total">
                                    <span>Total</span>
                                    <span>$387.96</span>
                                </div>
                            </div>
                            
                            <div class="shipping-info-review">
                                <h3>Shipping Information</h3>
                                <div class="info-content">
                                    <p><strong>John Doe</strong></p>
                                    <p>123 Main Street</p>
                                    <p>New York, NY 10001</p>
                                    <p>United States</p>
                                    <p>john.doe@email.com</p>
                                    <p>(555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="payment-info-review">
                                <h3>Payment Information</h3>
                                <div class="info-content">
                                    <p><strong>Credit Card</strong></p>
                                    <p>Visa ending in 3456</p>
                                    <p>Expires: 12/25</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="terms-agreement">
                            <div class="form-field checkbox-field">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">I agree to the <a href="terms.php">Terms and Conditions</a> and <a href="privacy.php">Privacy Policy</a> *</label>
                            </div>
                            <div class="form-field checkbox-field">
                                <input type="checkbox" id="newsletter" name="newsletter">
                                <label for="newsletter">Subscribe to our newsletter for updates and offers</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="checkout-navigation">
                        <button type="button" class="btn btn-outline" id="prevBtn">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Continue to Payment</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">Place Order</button>
                    </div>
                </form>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="checkout-sidebar">
                <div class="order-summary-card">
                    <h3>Order Summary</h3>
                    
                    <div class="order-items-preview">
                        <div class="order-preview-item">
                            <div class="preview-item-image">
                                <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w-60&q=80" alt="Denim Jacket" loading="lazy">
                            </div>
                            <div class="preview-item-details">
                                <h4>Premium Denim Jacket</h4>
                                <p>1 × $89.99</p>
                            </div>
                        </div>
                        <div class="order-preview-item">
                            <div class="preview-item-image">
                                <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=60&q=80" alt="Running Shoes" loading="lazy">
                            </div>
                            <div class="preview-item-details">
                                <h4>Leather Running Shoes</h4>
                                <p>2 × $129.99</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$349.97</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>$9.99</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span>$28.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>$387.96</span>
                        </div>
                    </div>
                </div>
                
                <div class="security-info">
                    <h4><i class="fas fa-lock"></i> Secure Checkout</h4>
                    <p>Your payment information is encrypted and secure.</p>
                    
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>SSL Secure</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-credit-card"></i>
                            <span>PCI Compliant</span>
                        </div>
                    </div>
                </div>
                
                <div class="need-help">
                    <h4><i class="fas fa-question-circle"></i> Need Help?</h4>
                    <p>Call us at <strong>1-555-123-4567</strong> or <a href="contact.php">Contact Support</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>

<!-- Checkout Page CSS -->
<style>
/* Checkout Progress */
.checkout-progress {
    display: flex;
    justify-content: center;
    gap: var(--spacing-lg);
    margin: var(--spacing-lg) 0;
    position: relative;
}

.checkout-progress::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 2px;
    background-color: var(--light-gray);
    z-index: 1;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    z-index: 2;
    position: relative;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: white;
    border: 2px solid var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--gray-color);
    transition: var(--transition-medium);
}

.step-label {
    font-weight: 500;
    color: var(--gray-color);
    font-size: 0.9rem;
    transition: var(--transition-medium);
}

.progress-step.active .step-number {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
    color: white;
}

.progress-step.active .step-label {
    color: var(--secondary-color);
    font-weight: 600;
}

/* Checkout Content */
.checkout-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: var(--spacing-lg);
}

/* Checkout Form */
.checkout-form {
    background-color: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
}

.checkout-step {
    display: none;
}

.checkout-step.active {
    display: block;
}

.checkout-step h2 {
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--light-gray);
}

/* Form Styles */
.form-group {
    margin-bottom: var(--spacing-md);
}

.form-group.two-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
}

.form-field {
    margin-bottom: var(--spacing-sm);
}

.form-field label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.form-field input,
.form-field select,
.form-field textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-md);
    font-family: var(--font-main);
    font-size: 1rem;
    color: var(--dark-color);
    transition: var(--transition-fast);
}

.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(255, 77, 109, 0.1);
}

.form-field.checkbox-field {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-field.checkbox-field input {
    width: auto;
}

.form-field.checkbox-field label {
    margin-bottom: 0;
}

/* Shipping Options */
.shipping-options {
    display: grid;
    gap: 0.5rem;
}

.shipping-option {
    position: relative;
}

.shipping-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.shipping-option label {
    display: grid;
    grid-template-columns: 1fr auto;
    padding: 1rem;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition-fast);
}

.shipping-option:hover label {
    border-color: var(--secondary-color);
}

.shipping-option input[type="radio"]:checked + label {
    background-color: rgba(255, 77, 109, 0.05);
    border-color: var(--secondary-color);
}

.option-name {
    font-weight: 500;
    color: var(--dark-color);
}

.option-desc {
    font-size: 0.9rem;
    color: var(--gray-color);
}

.option-price {
    grid-row: span 2;
    font-weight: 600;
    color: var(--dark-color);
}

/* Payment Method Selector */
.payment-method-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
}

.payment-method {
    position: relative;
}

.payment-method input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.payment-method label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 1px solid var(--light-gray);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition-fast);
    text-align: center;
}

.payment-method label i {
    font-size: 1.5rem;
    color: var(--gray-color);
}

.payment-method label span {
    font-weight: 500;
    color: var(--dark-color);
}

.payment-method:hover label {
    border-color: var(--secondary-color);
}

.payment-method input[type="radio"]:checked + label {
    background-color: rgba(255, 77, 109, 0.05);
    border-color: var(--secondary-color);
}

.payment-method input[type="radio"]:checked + label i {
    color: var(--secondary-color);
}

/* Payment Forms */
.payment-form {
    display: none;
    margin-bottom: var(--spacing-lg);
}

.payment-form.active {
    display: block;
}

.credit-card-form .card-icons {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.credit-card-form .card-icons i {
    font-size: 1.5rem;
    color: var(--gray-color);
}

.cvv-info {
    position: absolute;
    right: 10px;
    top: 40px;
    width: 20px;
    height: 20px;
    background-color: var(--light-gray);
    color: var(--gray-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    cursor: help;
}

.paypal-info,
.apple-pay-info {
    text-align: center;
    padding: var(--spacing-lg);
    border: 2px dashed var(--light-gray);
    border-radius: var(--radius-lg);
}

.btn-paypal {
    background-color: #003087;
    color: white;
    padding: 1rem 2rem;
    margin-top: var(--spacing-md);
}

.btn-paypal:hover {
    background-color: #002569;
}

.btn-apple-pay {
    background-color: #000000;
    color: white;
    padding: 1rem 2rem;
    margin-top: var(--spacing-md);
}

.btn-apple-pay:hover {
    background-color: #333333;
}

/* Billing Address */
.billing-address {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--light-gray);
}

.billing-form {
    display: none;
    margin-top: var(--spacing-md);
}

/* Order Review */
.order-summary {
    display: grid;
    gap: var(--spacing-lg);
}

.order-items,
.order-totals,
.shipping-info-review,
.payment-info-review {
    background-color: var(--light-color);
    border-radius: var(--radius-lg);
    padding: var(--spacing-md);
}

.order-items h3,
.order-totals h3,
.shipping-info-review h3,
.payment-info-review h3 {
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--light-gray);
}

.order-items-list {
    display: grid;
    gap: var(--spacing-sm);
}

.order-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid var(--light-gray);
}

.order-item:last-child {
    border-bottom: none;
}

.item-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details h4 {
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.item-details p {
    font-size: 0.9rem;
    color: var(--gray-color);
    margin: 0;
}

.item-quantity,
.item-price {
    text-align: center;
    font-weight: 500;
}

.order-totals .totals-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--light-gray);
}

.order-totals .totals-row:last-child {
    border-bottom: none;
}

.order-totals .totals-row.total {
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--dark-color);
}

.shipping-info-review .info-content p,
.payment-info-review .info-content p {
    margin: 0.5rem 0;
}

/* Terms Agreement */
.terms-agreement {
    margin: var(--spacing-lg) 0;
    padding: var(--spacing-md);
    background-color: var(--light-color);
    border-radius: var(--radius-lg);
}

/* Checkout Navigation */
.checkout-navigation {
    display: flex;
    justify-content: space-between;
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--light-gray);
}

/* Checkout Sidebar */
.checkout-sidebar {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.order-summary-card {
    background-color: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
}

.order-summary-card h3 {
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--light-gray);
}

.order-items-preview {
    display: grid;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}

.order-preview-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--light-gray);
}

.order-preview-item:last-child {
    border-bottom: none;
}

.preview-item-image {
    width: 50px;
    height: 50px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    flex-shrink: 0;
}

.preview-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-item-details h4 {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.preview-item-details p {
    font-size: 0.8rem;
    color: var(--gray-color);
    margin: 0;
}

.summary-totals {
    margin-top: var(--spacing-md);
}

.summary-totals .summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
}

.summary-totals .summary-row.total {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--dark-color);
    border-top: 1px solid var(--light-gray);
    margin-top: 0.5rem;
    padding-top: 1rem;
}

/* Security Info */
.security-info,
.need-help {
    background-color: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
}

.security-info h4,
.need-help h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: var(--spacing-sm);
}

.security-info h4 i {
    color: var(--secondary-color);
}

.need-help h4 i {
    color: var(--accent-color);
}

.trust-badges {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
}

.trust-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    padding: 1rem;
    background-color: var(--light-color);
    border-radius: var(--radius-md);
    text-align: center;
}

.trust-badge i {
    font-size: 1.5rem;
    color: var(--secondary-color);
}

.trust-badge span {
    font-size: 0.8rem;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 1200px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .checkout-progress {
        gap: var(--spacing-sm);
    }
    
    .checkout-progress::before {
        width: 90%;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .step-label {
        font-size: 0.8rem;
    }
    
    .form-group.two-columns {
        grid-template-columns: 1fr;
    }
    
    .payment-method-selector {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }
    
    .checkout-navigation {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .checkout-navigation .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .checkout-form {
        padding: var(--spacing-md);
    }
    
    .checkout-step h2 {
        font-size: 1.5rem;
    }
    
    .shipping-option label {
        grid-template-columns: 1fr;
        gap: 0.25rem;
    }
    
    .option-price {
        grid-row: auto;
    }
}
</style>

<!-- Checkout Page JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step checkout
    let currentStep = 1;
    const totalSteps = 3;
    
    const steps = document.querySelectorAll('.checkout-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    // Initialize
    showStep(currentStep);
    
    // Next button click
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    }
    
    // Previous button click
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }
    
    function showStep(step) {
        // Hide all steps
        steps.forEach(s => s.classList.remove('active'));
        
        // Show current step
        document.getElementById('step' + step).classList.add('active');
        
        // Update progress indicators
        progressSteps.forEach((p, index) => {
            if (index < step) {
                p.classList.add('active');
            } else {
                p.classList.remove('active');
            }
        });
        
        // Update navigation buttons
        if (step === 1) {
            prevBtn.style.display = 'none';
            nextBtn.textContent = 'Continue to Payment';
            submitBtn.style.display = 'none';
            nextBtn.style.display = 'block';
        } else if (step === 2) {
            prevBtn.style.display = 'block';
            nextBtn.textContent = 'Review Order';
            submitBtn.style.display = 'none';
            nextBtn.style.display = 'block';
        } else if (step === 3) {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        }
    }
    
    function validateStep(step) {
        const currentStepElement = document.getElementById('step' + step);
        const requiredInputs = currentStepElement.querySelectorAll('[required]');
        let isValid = true;
        
        // Remove previous error states
        requiredInputs.forEach(input => {
            input.classList.remove('error');
            const errorMsg = input.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });
        
        // Validate required fields
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
                
                const errorMsg = document.createElement('span');
                errorMsg.className = 'error-message';
                errorMsg.textContent = 'This field is required';
                errorMsg.style.color = '#f44336';
                errorMsg.style.fontSize = '0.8rem';
                errorMsg.style.display = 'block';
                errorMsg.style.marginTop = '0.25rem';
                
                input.parentNode.appendChild(errorMsg);
            }
        });
        
        // Step-specific validation
        if (step === 1) {
            // Validate email
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    isValid = false;
                    emailInput.classList.add('error');
                    
                    const errorMsg = document.createElement('span');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'Please enter a valid email address';
                    errorMsg.style.color = '#f44336';
                    errorMsg.style.fontSize = '0.8rem';
                    errorMsg.style.display = 'block';
                    errorMsg.style.marginTop = '0.25rem';
                    
                    emailInput.parentNode.appendChild(errorMsg);
                }
            }
            
            // Validate phone
            const phoneInput = document.getElementById('phone');
            if (phoneInput && phoneInput.value) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                if (!phoneRegex.test(phoneInput.value.replace(/[-\s()]/g, ''))) {
                    isValid = false;
                    phoneInput.classList.add('error');
                    
                    const errorMsg = document.createElement('span');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'Please enter a valid phone number';
                    errorMsg.style.color = '#f44336';
                    errorMsg.style.fontSize = '0.8rem';
                    errorMsg.style.display = 'block';
                    errorMsg.style.marginTop = '0.25rem';
                    
                    phoneInput.parentNode.appendChild(errorMsg);
                }
            }
        }
        
        if (!isValid) {
            // Scroll to first error
            const firstError = currentStepElement.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        return isValid;
    }
    
    // Payment method switching
    const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
    const paymentForms = document.querySelectorAll('.payment-form');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            const methodValue = this.value;
            
            // Hide all payment forms
            paymentForms.forEach(form => {
                form.classList.remove('active');
            });
            
            // Show selected payment form
            document.querySelector('.' + methodValue + '-form').classList.add('active');
        });
    });
    
    // Same as shipping address
    const sameAsShipping = document.getElementById('sameAsShipping');
    const billingForm = document.querySelector('.billing-form');
    
    if (sameAsShipping) {
        sameAsShipping.addEventListener('change', function() {
            if (this.checked) {
                billingForm.style.display = 'none';
            } else {
                billingForm.style.display = 'block';
            }
        });
    }
    
    // Credit card input formatting
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\s/g, '').replace(/[^0-9]/g, '');
            let formattedValue = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            this.value = formattedValue.substring(0, 19);
        });
    }
    
    const expiryDateInput = document.getElementById('expiryDate');
    if (expiryDateInput) {
        expiryDateInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            this.value = value.substring(0, 5);
        });
    }
    
    // CVV info tooltip
    const cvvInfo = document.querySelector('.cvv-info');
    if (cvvInfo) {
        cvvInfo.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = '3 or 4 digit code on back of card';
            tooltip.style.position = 'absolute';
            tooltip.style.background = '#333';
            tooltip.style.color = 'white';
            tooltip.style.padding = '0.5rem';
            tooltip.style.borderRadius = '4px';
            tooltip.style.fontSize = '0.8rem';
            tooltip.style.zIndex = '1000';
            tooltip.style.width = '200px';
            tooltip.style.bottom = '100%';
            tooltip.style.left = '50%';
            tooltip.style.transform = 'translateX(-50%)';
            
            this.appendChild(tooltip);
        });
        
        cvvInfo.addEventListener('mouseleave', function() {
            const tooltip = this.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    }
    
    // Form submission
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateStep(currentStep)) {
                // In a real app, this would submit to server
                // For now, show success message
                
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                submitBtn.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    alert('Order placed successfully! Thank you for your purchase.');
                    
                    // In a real app, redirect to confirmation page
                    window.location.href = 'order-confirmation.php';
                }, 2000);
            }
        });
    }
    
    // Update shipping cost in sidebar
    const shippingOptions = document.querySelectorAll('input[name="shippingMethod"]');
    const shippingCostElement = document.querySelector('.summary-row:nth-child(2) span:last-child');
    const totalElement = document.querySelector('.summary-row.total span:last-child');
    
    shippingOptions.forEach(option => {
        option.addEventListener('change', function() {
            const shippingCost = parseFloat(this.value === 'standard' ? 9.99 : this.value === 'express' ? 19.99 : 29.99);
            const subtotal = 349.97;
            const tax = subtotal * 0.08;
            const total = subtotal + shippingCost + tax;
            
            shippingCostElement.textContent = '$' + shippingCost.toFixed(2);
            totalElement.textContent = '$' + total.toFixed(2);
        });
    });
    
    // PayPal button
    const paypalBtn = document.querySelector('.btn-paypal');
    if (paypalBtn) {
        paypalBtn.addEventListener('click', function() {
            // In a real app, this would redirect to PayPal
            alert('Redirecting to PayPal...');
        });
    }
    
    // Apple Pay button
    const applePayBtn = document.querySelector('.btn-apple-pay');
    if (applePayBtn) {
        applePayBtn.addEventListener('click', function() {
            // In a real app, this would initiate Apple Pay
            alert('Initiating Apple Pay...');
        });
    }
});
</script>