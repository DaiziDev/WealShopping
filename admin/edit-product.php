<?php
// admin/edit-product.php
require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id === 0) {
    header('Location: products.php');
    exit();
}

// Get product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit();
}

// Get product images
$sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_main DESC, sort_order";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$images = $stmt->fetchAll();

// Get product attributes
$sql = "SELECT * FROM product_attributes WHERE product_id = ? ORDER BY id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$attributes = $stmt->fetchAll();

// Get categories
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's an image upload
    if (isset($_FILES['new_images']) && !empty($_FILES['new_images']['name'][0])) {
        // Handle new image upload
        $upload_dir = '../../assets/images/products/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Process each uploaded file
        for ($i = 0; $i < count($_FILES['new_images']['name']); $i++) {
            if ($_FILES['new_images']['error'][$i] == 0) {
                $file_name = time() . '_' . $product_id . '_' . basename($_FILES['new_images']['name'][$i]);
                $file_tmp = $_FILES['new_images']['tmp_name'][$i];
                $file_path = $upload_dir . $file_name;
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Store image URL in database
                    $image_url = 'assets/images/products/' . $file_name;
                    $sql = "INSERT INTO product_images (product_id, image_url, is_main, sort_order) VALUES (?, ?, 0, ?)";
                    $stmt = $pdo->prepare($sql);
                    
                    // Get next sort order
                    $sort_order = count($images) + $i;
                    $stmt->execute([$product_id, $image_url, $sort_order]);
                }
            }
        }
        
        $_SESSION['success_message'] = "Images uploaded successfully!";
        header('Location: edit-product.php?id=' . $product_id);
        exit();
    } else {
        // Handle product update
        $name = trim($_POST['name']);
        $slug = trim($_POST['slug']);
        $description = trim($_POST['description']);
        $short_description = trim($_POST['short_description']);
        $price = floatval($_POST['price']);
        $compare_price = !empty($_POST['compare_price']) ? floatval($_POST['compare_price']) : null;
        $sku = trim($_POST['sku']);
        $quantity = intval($_POST['quantity']);
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $brand = trim($_POST['brand']);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Check if SKU already exists (excluding current product)
        $sql = "SELECT id FROM products WHERE sku = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sku, $product_id]);
        
        if ($stmt->rowCount() > 0) {
            $error = "SKU already exists. Please use a different SKU.";
        } else {
            // Update product
            $sql = "UPDATE products SET 
                    name = ?, slug = ?, description = ?, short_description = ?, 
                    price = ?, compare_price = ?, sku = ?, quantity = ?, 
                    category_id = ?, brand = ?, featured = ?, is_active = ?, 
                    updated_at = NOW() 
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $name, $slug, $description, $short_description,
                $price, $compare_price, $sku, $quantity,
                $category_id, $brand, $featured, $is_active,
                $product_id
            ]);
            
            $_SESSION['success_message'] = "Product updated successfully!";
            header('Location: edit-product.php?id=' . $product_id);
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - WealShopping Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
        
        .product-images-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        
        .existing-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .product-image {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            height: 120px;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            padding: 5px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        
        .image-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            background: white;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
        
        .image-btn:hover {
            background: #4361ee;
            color: white;
        }
        
        .main-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: #4361ee;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 0.75rem;
        }
        
        /* New Image Upload Styles */
        .new-image-upload {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: #fff;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .new-image-upload:hover {
            border-color: #4361ee;
            background: #f0f4ff;
        }
        
        .upload-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4361ee;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin: 10px 0;
        }
        
        .upload-btn:hover {
            background: #3a0ca3;
        }
        
        .image-input {
            display: none;
        }
        
        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .preview-image {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            height: 100px;
        }
        
        .preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-preview {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="admin-main">
            <header class="admin-header">
                <h1>Edit Product</h1>
                <div>
                    <a href="products.php" class="btn">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </header>

            <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <div class="form-container">
                <!-- Product Images Section -->
                <div class="product-images-section">
                    <h3 style="margin-bottom: 15px; color: #333;">Product Images</h3>
                    
                    <?php if (!empty($images)): ?>
                    <div class="existing-images" id="existingImages">
                        <?php foreach($images as $image): ?>
                        <div class="product-image" data-image-id="<?php echo $image['id']; ?>">
                            <img src="<?php echo $image['image_url']; ?>" alt="Product Image">
                            <?php if ($image['is_main']): ?>
                            <span class="main-badge">Main</span>
                            <?php endif; ?>
                            <div class="image-actions">
                                <?php if (!$image['is_main']): ?>
                                <button class="image-btn set-main-btn" title="Set as Main">
                                    <i class="fas fa-star"></i>
                                </button>
                                <?php endif; ?>
                                <button class="image-btn delete-image-btn" title="Delete Image">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #999; font-style: italic; text-align: center; padding: 20px;">
                        No images uploaded for this product.
                    </p>
                    <?php endif; ?>
                    
                    <!-- Add More Images Form -->
                    <div class="new-image-upload">
                        <h4 style="margin-bottom: 10px; color: #666;">Add More Images</h4>
                        <p style="color: #888; margin-bottom: 15px;">Click to select images from your computer</p>
                        <label for="new_images" class="upload-btn">
                            <i class="fas fa-plus"></i> Add Images
                        </label>
                        <input type="file" id="new_images" name="new_images[]" class="image-input" multiple accept="image/*">
                        <div class="image-preview" id="imagePreview"></div>
                        
                        <form method="POST" enctype="multipart/form-data" id="uploadForm">
                            <input type="hidden" name="upload_images" value="1">
                            <div id="imageInputsContainer"></div>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 15px;" id="uploadImagesBtn" disabled>
                                <i class="fas fa-upload"></i> Upload Selected Images
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Product Details Form -->
                <form method="POST">
                    <input type="hidden" name="update_product" value="1">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">URL Slug *</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="<?php echo htmlspecialchars($product['slug']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sku">SKU *</label>
                            <input type="text" id="sku" name="sku" class="form-control" value="<?php echo htmlspecialchars($product['sku']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (FCFA) *</label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="compare_price">Compare Price ($)</label>
                            <input type="number" id="compare_price" name="compare_price" class="form-control" step="0.01" min="0" value="<?php echo $product['compare_price']; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" min="0" value="<?php echo $product['quantity']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" id="brand" name="brand" class="form-control" value="<?php echo htmlspecialchars($product['brand'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <textarea id="short_description" name="short_description" class="form-control"><?php echo htmlspecialchars($product['short_description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Full Description</label>
                        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Product Attributes -->
                    <div class="form-group">
                        <label>Product Attributes</label>
                        <?php if (!empty($attributes)): ?>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
                            <table style="width: 100%;">
                                <?php foreach($attributes as $attr): ?>
                                <tr>
                                    <td style="padding: 5px 10px; border-bottom: 1px solid #eee;">
                                        <strong><?php echo htmlspecialchars($attr['attribute_name']); ?>:</strong>
                                        <?php echo htmlspecialchars($attr['attribute_value']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <?php else: ?>
                        <p style="color: #999; font-style: italic;">No attributes defined for this product.</p>
                        <?php endif; ?>
                        <a href="manage-attributes.php?product_id=<?php echo $product_id; ?>" class="btn btn-sm btn-primary" style="margin-top: 10px;">
                            <i class="fas fa-list"></i> Manage Attributes
                        </a>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="featured" name="featured" value="1" <?php echo $product['featured'] ? 'checked' : ''; ?>>
                                <label for="featured">Featured Product</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo $product['is_active'] ? 'checked' : ''; ?>>
                                <label for="is_active">Active Product</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                        <a href="products.php" class="btn">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Auto-generate slug from product name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-')
                .trim();
            
            document.getElementById('slug').value = slug;
        });
        
        // Image upload functionality for edit page
        const imageInput = document.getElementById('new_images');
        const imagePreview = document.getElementById('imagePreview');
        const uploadImagesBtn = document.getElementById('uploadImagesBtn');
        const imageInputsContainer = document.getElementById('imageInputsContainer');
        let selectedFiles = [];
        
        imageInput.addEventListener('change', function() {
            const files = this.files;
            imagePreview.innerHTML = '';
            selectedFiles = [];
            
            if (files.length > 0) {
                // Limit to 10 images
                const maxFiles = Math.min(files.length, 10);
                
                for (let i = 0; i < maxFiles; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'preview-image';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        
                        const removeBtn = document.createElement('div');
                        removeBtn.className = 'remove-preview';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.onclick = function() {
                            previewDiv.remove();
                            selectedFiles = selectedFiles.filter(f => f !== file);
                            updateUploadButton();
                        };
                        
                        previewDiv.appendChild(img);
                        previewDiv.appendChild(removeBtn);
                        imagePreview.appendChild(previewDiv);
                        
                        selectedFiles.push(file);
                    };
                    
                    reader.readAsDataURL(file);
                }
                
                updateUploadButton();
            }
        });
        
        function updateUploadButton() {
            if (selectedFiles.length > 0) {
                uploadImagesBtn.disabled = false;
            } else {
                uploadImagesBtn.disabled = true;
            }
        }
        
        // Handle image upload form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Create FormData object
            const formData = new FormData();
            formData.append('upload_images', '1');
            
            // Append each file
            selectedFiles.forEach((file, index) => {
                formData.append('new_images[]', file);
            });
            
            // Send AJAX request
            fetch('edit-product.php?id=<?php echo $product_id; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Reload page to show new images
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading images. Please try again.');
            });
        });
        
        // Set image as main (AJAX)
        document.querySelectorAll('.set-main-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const imageDiv = this.closest('.product-image');
                const imageId = imageDiv.getAttribute('data-image-id');
                
                if (confirm('Set this image as main product image?')) {
                    fetch('set-main-image.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'product_id=<?php echo $product_id; ?>&image_id=' + imageId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error setting main image.');
                    });
                }
            });
        });
        
        // Delete image (AJAX)
        document.querySelectorAll('.delete-image-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const imageDiv = this.closest('.product-image');
                const imageId = imageDiv.getAttribute('data-image-id');
                
                if (confirm('Are you sure you want to delete this image?')) {
                    fetch('delete-image.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'product_id=<?php echo $product_id; ?>&image_id=' + imageId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            imageDiv.remove();
                            if (document.querySelectorAll('.product-image').length === 0) {
                                document.getElementById('existingImages').innerHTML = 
                                    '<p style="color: #999; font-style: italic; text-align: center; padding: 20px;">No images uploaded for this product.</p>';
                            }
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting image.');
                    });
                }
            });
        });
    </script>
</body>
</html>