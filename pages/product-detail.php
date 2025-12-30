<?php
require_once '../includes/config.php';

if (!isset($_GET['slug'])) {
    header('Location: shop.php');
    exit();
}

$slug = sanitize($_GET['slug']);
$product = getProductBySlug($slug);

if (!$product) {
    header('Location: shop.php');
    exit();
}

$product_images = getProductImages($product['id']);
$product_attributes = getProductAttributes($product['id']);

// Get related products
$sql = "SELECT p.*, pi.image_url 
        FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1 AND p.quantity > 0
        LIMIT 4";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product['category_id'], $product['id']]);
$related_products = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<section class="product-detail section-padding">
    <div class="container">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> / 
            <a href="shop.php">Shop</a> / 
            <?php if ($product['category_slug']): ?>
            <a href="shop.php?category=<?php echo $product['category_slug']; ?>"><?php echo $product['category_name']; ?></a> / 
            <?php endif; ?>
            <span><?php echo $product['name']; ?></span>
        </div>
        
        <div class="product-main">
            <!-- Product Images -->
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo $product_images[0]['image_url'] ?: '../assets/images/still-life-rendering-jackets-display.jpg'; ?>" 
                         alt="<?php echo $product_images[0]['alt_text'] ?: $product['name']; ?>" 
                         id="mainImage">
                </div>
                <div class="thumbnail-images">
                    <?php foreach ($product_images as $image): ?>
                    <div class="thumbnail <?php echo $image['is_main'] ? 'active' : ''; ?>" 
                         data-image="<?php echo $image['image_url']; ?>">
                        <img src="http://localhost/fashion-shop/<?php echo $image['image_url']; ?>" alt ="<?php echo $image['alt_text']; ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title"><?php echo $product['name']; ?></h1>
                
                <div class="product-meta">
                    <?php if ($product['sku']): ?>
                    <span class="sku">SKU: <?php echo $product['sku']; ?></span>
                    <?php endif; ?>
                    <span class="stock <?php echo $product['quantity'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                        <?php echo $product['quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                </div>
                
                <div class="product-price">
                    <p class="price"><?php echo format_price($product['price']); ?></p>
                    <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                    <span class="original-price">$<?php echo number_format($product['compare_price'], 2); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <?php echo nl2br($product['description']); ?>
                </div>
                
                <?php if (count($product_attributes) > 0): ?>
                <div class="product-specs">
                    <h3>Specifications</h3>
                    <table class="specs-table">
                        <?php foreach ($product_attributes as $attr): ?>
                        <tr>
                            <td><?php echo $attr['attribute_name']; ?></td>
                            <td><?php echo $attr['attribute_value']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
                
                <?php if ($product['quantity'] > 0): ?>
                <form class="add-to-cart-form" id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-control">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                        <span class="max-quantity">Max: <?php echo $product['quantity']; ?></span>
                    </div>
                    
                    <div class="product-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline btn-lg wishlist-btn" data-product-id="<?php echo $product['id']; ?>">
                            <i class="far fa-heart"></i> Add to Wishlist
                        </button>
                    </div>
                </form>
                <?php else: ?>
                <div class="out-of-stock-notice">
                    <p class="alert alert-warning">This product is currently out of stock. Check back soon!</p>
                </div>
                <?php endif; ?>
                
                <div class="product-share">
                    <span>Share:</span>
                    <div class="social-share">
                        <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="share-btn instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tabs">
                <button class="tab-btn active" data-tab="description">Description</button>
                <button class="tab-btn" data-tab="reviews">Reviews (0)</button>
                <button class="tab-btn" data-tab="shipping">Shipping & Returns</button>
            </div>
            
            <div class="tab-content active" id="description">
                <?php echo nl2br($product['description']); ?>
            </div>
            
            <div class="tab-content" id="reviews">
                <?php if (isLoggedIn()): ?>
                <div class="add-review">
                    <h3>Add a Review</h3>
                    <form class="review-form">
                        <div class="rating-input">
                            <span>Rating:</span>
                            <div class="stars">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
                                <label for="star<?php echo $i; ?>"><i class="far fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Review Title" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Your Review" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
                <?php else: ?>
                <div class="login-to-review">
                    <p>Please <a href="login.php">login</a> to leave a review.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="tab-content" id="shipping">
                <h3>Shipping Information</h3>
                <p>We offer shipping across Cameroon. Delivery times vary by location:</p>
                <ul>
                    <li><strong>Douala & Yaoundé:</strong> 1-2 business days</li>
                    <li><strong>Other Major Cities:</strong> 2-4 business days</li>
                    <li><strong>Remote Areas:</strong> 5-7 business days</li>
                </ul>
                
                <h3>Returns & Exchanges</h3>
                <p>We accept returns within 14 days of delivery. Items must be unworn, unwashed, and in original packaging with tags attached.</p>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if (count($related_products) > 0): ?>
        <div class="related-products">
            <h2>You May Also Like</h2>
            <div class="products-grid">
                <?php foreach ($related_products as $related): ?>
                <div class="product-card">
                    <div class="product-image" style="background-image: url('<?php echo $related['image_url'] ?: '../assets/images/still-life-rendering-jackets-display.jpg'; ?>');">
                        <?php if ($related['compare_price'] && $related['compare_price'] > $related['price']): ?>
                        <span class="product-badge sale">Sale</span>
                        <?php endif; ?>
                        <button class="wishlist-btn" data-product-id="<?php echo $related['id']; ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="product-detail.php?slug=<?php echo $related['slug']; ?>"><?php echo $related['name']; ?></a>
                        </h3>
                        <div class="product-price">
                            <span class="current-price">$<?php echo number_format($related['price'], 2); ?></span>
                            <?php if ($related['compare_price'] && $related['compare_price'] > $related['price']): ?>
                            <span class="original-price">$<?php echo number_format($related['compare_price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-sm add-to-cart" data-product-id="<?php echo $related['id']; ?>" data-product-name="<?php echo htmlspecialchars($related['name']); ?>" data-product-price="<?php echo $related['price']; ?>">
                            Add to Cart
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    // Image gallery
    $('.thumbnail').click(function() {
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');
        $('#mainImage').attr('src', $(this).data('image'));
    });
    
    // Quantity control
    $('.quantity-btn.minus').click(function() {
        var input = $(this).siblings('input');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });
    
    $('.quantity-btn.plus').click(function() {
        var input = $(this).siblings('input');
        var max = parseInt(input.attr('max'));
        var value = parseInt(input.val());
        if (value < max) {
            input.val(value + 1);
        }
    });
    
    // Add to cart
    $('#addToCartForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: '../includes/add-to-cart.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('.cart-count').text(result.cartCount);
                    showNotification('Product added to cart!', 'success');
                } else {
                    showNotification(result.message, 'error');
                }
            }
        });
    });
    
    // Tabs
    $('.tab-btn').click(function() {
        var tabId = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').removeClass('active');
        $('#' + tabId).addClass('active');
    });
});
</script>

<?php
require_once '../includes/footer.php';
?>