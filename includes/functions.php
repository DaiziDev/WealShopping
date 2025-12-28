<?php
// includes/functions.php
function getProductMainImage($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ? AND is_main = 1 LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['image_url'];
    }
    
    // If no main image, get first image
    $stmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['image_url'];
    }
    
    return null;
}

function getCategories() {
    global $conn;
    $result = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
    $categories = [];
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

function getFeaturedProducts($limit = 8) {
    global $conn;
    $stmt = $conn->prepare("SELECT p.*, pi.image_url 
                           FROM products p 
                           LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                           WHERE p.featured = 1 AND p.is_active = 1
                           ORDER BY p.created_at DESC 
                           LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

function base_url($path = '') {
    return 'http://' . $_SERVER['HTTP_HOST'] . '/fashion-shop/' . $path;
}

function asset_url($path = '') {
    return base_url('assets/' . $path);
}

function page_url($path = '') {
    return base_url('pages/' . $path);
}

function admin_url($path = '') {
    return base_url('admin/' . $path);
}
?>