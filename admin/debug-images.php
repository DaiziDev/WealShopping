<?php
// debug-images.php
require_once 'includes/config.php';

echo "<h1>Image Debug Information</h1>";

// Check database connection
echo "<h2>Database Connection</h2>";
try {
    $test = $pdo->query("SELECT 1")->fetch();
    echo "Database connection: <span style='color:green'>OK</span><br>";
} catch (Exception $e) {
    echo "Database connection: <span style='color:red'>FAILED - " . $e->getMessage() . "</span><br>";
}

// Check what's in product_images table
echo "<h2>Product Images in Database</h2>";
$sql = "SELECT pi.*, p.name FROM product_images pi 
        JOIN products p ON pi.product_id = p.id 
        ORDER BY pi.product_id, pi.is_main DESC";
$stmt = $pdo->query($sql);
$images = $stmt->fetchAll();

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Product Name</th><th>Image URL</th><th>Is Main</th><th>File Exists?</th><th>Full Path</th></tr>";

foreach ($images as $img) {
    // Check different path possibilities
    $paths_to_check = [
        '../' . $img['image_url'],
        $img['image_url'],
        '../../' . $img['image_url'],
        'assets/images/products/' . basename($img['image_url'])
    ];
    
    $file_exists = false;
    $found_path = '';
    
    foreach ($paths_to_check as $path) {
        if (file_exists($path)) {
            $file_exists = true;
            $found_path = $path;
            break;
        }
    }
    
    echo "<tr>";
    echo "<td>{$img['id']}</td>";
    echo "<td>{$img['name']}</td>";
    echo "<td><code>{$img['image_url']}</code></td>";
    echo "<td>" . ($img['is_main'] ? 'Yes' : 'No') . "</td>";
    echo "<td style='color:" . ($file_exists ? 'green' : 'red') . "'>" . ($file_exists ? 'Yes' : 'No') . "</td>";
    echo "<td><small>" . htmlspecialchars($found_path) . "</small></td>";
    echo "</tr>";
}

echo "</table>";

// Check directory structure
echo "<h2>Directory Structure Check</h2>";
$base_dir = __DIR__;
echo "Base directory: " . $base_dir . "<br>";

$paths = [
    'assets/images/products/' => 'assets/images/products/',
    '../assets/images/products/' => '../assets/images/products/',
    '../../assets/images/products/' => '../../assets/images/products/',
    __DIR__ . '/assets/images/products/' => 'Full path to products folder'
];

foreach ($paths as $relative => $label) {
    $full_path = realpath($relative);
    echo "<b>$label</b>: " . ($full_path ? $full_path : "Doesn't exist") . "<br>";
    if ($full_path && is_dir($full_path)) {
        // List files
        $files = scandir($full_path);
        $image_files = array_filter($files, function($file) {
            return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
        });
        echo "Contains " . count($image_files) . " image files<br>";
    }
    echo "<hr>";
}

// Test the display_product_image function
echo "<h2>Test display_product_image() Function</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Input</th><th>Output</th><th>File Exists?</th></tr>";

$test_urls = [
    'assets/images/products/test.jpg',
    '../assets/images/products/test.jpg',
    'images/products/test.jpg',
    'https://images.unsplash.com/photo-1591047139829-d91aecb6caea',
    'assets/images/still-life-rendering-jackets-display.jpg'
];

foreach ($test_urls as $test_url) {
    $output = display_product_image($test_url);
    $exists = file_exists($output) || file_exists(str_replace('../', '', $output)) || strpos($output, 'http') === 0;
    
    echo "<tr>";
    echo "<td><code>$test_url</code></td>";
    echo "<td><code>$output</code></td>";
    echo "<td style='color:" . ($exists ? 'green' : 'red') . "'>" . ($exists ? 'Yes' : 'No') . "</td>";
    echo "</tr>";
}

echo "</table>";

// Add a test product
echo "<h2>Add Test Product Form</h2>";
?>
<form action="admin/add-product.php" method="POST" enctype="multipart/form-data" style="background:#f5f5f5; padding:20px; border-radius:5px;">
    <h3>Quick Test Product</h3>
    <div>
        <label>Product Name:</label><br>
        <input type="text" name="name" value="Test Product <?php echo time(); ?>" required>
    </div>
    <div>
        <label>Price (FCFA):</label><br>
        <input type="number" name="price" value="10000" required>
    </div>
    <div>
        <label>SKU:</label><br>
        <input type="text" name="sku" value="TEST-<?php echo rand(1000,9999); ?>" required>
    </div>
    <div>
        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="10" required>
    </div>
    <div>
        <label>Test Image:</label><br>
        <input type="file" name="images[]" accept="image/*">
    </div>
    <div style="margin-top:10px;">
        <input type="submit" value="Add Test Product">
    </div>
</form>
<?php
// Show current URL info
echo "<h2>Current URL Information</h2>";
echo "SITE_URL: " . SITE_URL . "<br>";
echo "Script: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
?>