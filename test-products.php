<?php
// diagnose.php
require_once '../includes/config.php';

echo "<h1>Diagnostic Information</h1>";

// Test database connection
try {
    // Test products count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $result = $stmt->fetch();
    echo "<p>Total products in database: <strong>" . $result['total'] . "</strong></p>";
    
    // Test 5 products
    $stmt = $pdo->query("SELECT id, name, price, category_id FROM products LIMIT 5");
    $products = $stmt->fetchAll();
    
    echo "<h2>Sample Products:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Category ID</th></tr>";
    foreach ($products as $product) {
        echo "<tr>";
        echo "<td>{$product['id']}</td>";
        echo "<td>{$product['name']}</td>";
        echo "<td>{$product['price']}</td>";
        echo "<td>{$product['category_id'] ?? 'N/A'}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test categories
    echo "<h2>Categories:</h2>";
    $stmt = $pdo->query("SELECT id, name, slug FROM categories");
    $categories = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Slug</th></tr>";
    foreach ($categories as $category) {
        echo "<tr>";
        echo "<td>{$category['id']}</td>";
        echo "<td>{$category['name']}</td>";
        echo "<td>{$category['slug']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test if product_categories table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM product_categories");
        $result = $stmt->fetch();
        echo "<p>Product-category relationships: <strong>" . $result['total'] . "</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>product_categories table doesn't exist or has errors</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

// Test the getProducts function
echo "<h2>Testing getProducts() function:</h2>";
$test_products = getProducts('', '', 0, 1000, 'newest', 5, 0);
echo "<p>Function returned: " . count($test_products) . " products</p>";

// Check if products are in price range
echo "<h2>Price Distribution:</h2>";
$stmt = $pdo->query("SELECT MIN(price) as min_price, MAX(price) as max_price, AVG(price) as avg_price FROM products");
$price_stats = $stmt->fetch();
echo "<p>Min Price: {$price_stats['min_price']}</p>";
echo "<p>Max Price: {$price_stats['max_price']}</p>";
echo "<p>Average Price: {$price_stats['avg_price']}</p>";
?>