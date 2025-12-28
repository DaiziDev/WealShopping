<?php
// admin/add-product.php
require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
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
    
    // Check if SKU already exists
    $sql = "SELECT id FROM products WHERE sku = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sku]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error_message'] = "SKU already exists. Please use a different SKU.";
        header('Location: add-product.php');
        exit();
    } else {
        // Insert product
        $sql = "INSERT INTO products (name, slug, description, short_description, price, compare_price, sku, quantity, category_id, brand, featured, is_active, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name, $slug, $description, $short_description,
            $price, $compare_price, $sku, $quantity,
            $category_id, $brand, $featured, $is_active
        ]);
        
        $product_id = $pdo->lastInsertId();
        
        // Handle image upload
        $uploaded_images = 0;
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $upload_dir = '../../assets/images/products/';  // Add products folder
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    $_SESSION['error_message'] = 'Failed to create upload directory. Check permissions.';
                    header('Location: add-product.php');
                    exit();
                }
            }
            
            // Process each uploaded file
            $main_image_set = false;
            
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($_FILES['images']['error'][$i] == 0) {
                    // Get file extension
                    $file_extension = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    
                    // Generate safe filename - replace spaces with underscores and remove special chars
                    $original_name = pathinfo($_FILES['images']['name'][$i], PATHINFO_FILENAME);
                    $safe_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name);
                    $file_name = time() . '_' . $i . '_' . $safe_name . '.' . $file_extension;
                    $file_tmp = $_FILES['images']['tmp_name'][$i];
                    $file_path = $upload_dir . $file_name;
                    
                    // Check file type
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($file_extension, $allowed_extensions)) {
                        $_SESSION['warning_message'] = "Skipped invalid file type: " . $_FILES['images']['name'][$i] . ". Only JPG, PNG, GIF, and WebP are allowed.";
                        continue;
                    }
                    
                    // Check file size (max 5MB)
                    if ($_FILES['images']['size'][$i] > 5242880) {
                        $_SESSION['warning_message'] = "Skipped large file: " . $_FILES['images']['name'][$i] . ". Maximum size is 5MB.";
                        continue;
                    }
                    
                    if (move_uploaded_file($file_tmp, $file_path)) {
                        // First image is set as main
                        $is_main = $main_image_set ? 0 : 1;
                        $main_image_set = true;
                        
                        // Store image URL in database - store as 'assets/images/filename.jpg' / CHANGED: Removed /products/
                        $image_url = 'assets/images/products/' . $file_name;  // Add products folder
                        $sql = "INSERT INTO product_images (product_id, image_url, is_main, sort_order) VALUES (?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$product_id, $image_url, $is_main, $i]);
                        
                        $uploaded_images++;
                    } else {
                        $_SESSION['warning_message'] = "Failed to upload image: " . $_FILES['images']['name'][$i];
                    }
                }
            }
        }
        
        if ($uploaded_images > 0) {
            $_SESSION['success_message'] = "Product added successfully with $uploaded_images image(s)!";
        } else {
            $_SESSION['success_message'] = "Product added successfully! (No images uploaded)";
        }
        
        header('Location: products.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - WealShopping Admin</title>
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
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        /* Image Upload Styles */
        .image-upload-section {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: #f9f9f9;
            margin-bottom: 20px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .image-upload-section:hover {
            border-color: #4361ee;
            background: #f0f4ff;
        }
        
        .image-upload-section i {
            font-size: 3rem;
            color: #4361ee;
            margin-bottom: 15px;
        }
        
        .image-upload-section p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .preview-image {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            height: 120px;
        }
        
        .preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
        }
        
        .image-input {
            display: none;
        }
        
        .upload-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4361ee;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .upload-btn:hover {
            background: #3a0ca3;
        }
        
        .selected-files {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="admin-main">
            <header class="admin-header">
                <h1>Add New Product</h1>
                <div>
                    <a href="products.php" class="btn">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </header>

            <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['warning_message'])): ?>
            <div class="alert alert-warning">
                <?php 
                echo $_SESSION['warning_message'];
                unset($_SESSION['warning_message']);
                ?>
            </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">URL Slug *</label>
                            <input type="text" id="slug" name="slug" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sku">SKU *</label>
                            <input type="text" id="sku" name="sku" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (FCFA) *</label>
                            <input type="number" id="price" name="price" class="form-control" step="1000" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="compare_price">Compare Price (FCFA)</label>
                            <input type="number" id="compare_price" name="compare_price" class="form-control" step="1000" min="0">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" min="0" value="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" id="brand" name="brand" class="form-control">
                        </div>
                    </div>
                    
                    <!-- Image Upload Section -->
                    <div class="form-group">
                        <label>Product Images</label>
                        <div class="image-upload-section" id="imageUploadArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop product images here or click to browse</p>
                            <label for="images" class="upload-btn">
                                <i class="fas fa-folder-open"></i> Choose Images
                            </label>
                            <input type="file" id="images" name="images[]" class="image-input" multiple accept="image/*">
                            <p class="selected-files">No images selected</p>
                        </div>
                        <div class="image-preview" id="imagePreview"></div>
                        <small style="color: #666;">First image will be set as main product image. Maximum 10 images. Allowed: JPG, PNG, GIF, WebP (max 5MB each)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <textarea id="short_description" name="short_description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Full Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="featured" name="featured" value="1">
                                <label for="featured">Featured Product</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label for="is_active">Active Product</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Product
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
        
        // Auto-generate SKU from product name and random number
        document.getElementById('name').addEventListener('blur', function() {
            const skuField = document.getElementById('sku');
            if (skuField.value === '') {
                const name = this.value;
                const initials = name.substring(0, 3).toUpperCase();
                const random = Math.floor(Math.random() * 10000);
                skuField.value = initials + '-' + random.toString().padStart(4, '0');
            }
        });
        
        // Image upload functionality
        const imageInput = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');
        const selectedFilesText = document.querySelector('.selected-files');
        const imageUploadArea = document.getElementById('imageUploadArea');
        
        // Handle drag and drop
        imageUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUploadArea.style.borderColor = '#4361ee';
            imageUploadArea.style.background = '#f0f4ff';
        });
        
        imageUploadArea.addEventListener('dragleave', () => {
            imageUploadArea.style.borderColor = '#ddd';
            imageUploadArea.style.background = '#f9f9f9';
        });
        
        imageUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUploadArea.style.borderColor = '#ddd';
            imageUploadArea.style.background = '#f9f9f9';
            
            if (e.dataTransfer.files) {
                imageInput.files = e.dataTransfer.files;
                updateImagePreview();
            }
        });
        
        // Handle file input change
        imageInput.addEventListener('change', updateImagePreview);
        
        function updateImagePreview() {
            imagePreview.innerHTML = '';
            const files = imageInput.files;
            
            if (files.length > 0) {
                selectedFilesText.textContent = files.length + ' image(s) selected';
                
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
                        removeBtn.className = 'remove-image';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.onclick = function() {
                            previewDiv.remove();
                            updateFileList(i);
                        };
                        
                        previewDiv.appendChild(img);
                        previewDiv.appendChild(removeBtn);
                        imagePreview.appendChild(previewDiv);
                    };
                    
                    reader.readAsDataURL(file);
                }
                
                if (files.length > 10) {
                    selectedFilesText.textContent += ' (Only first 10 will be uploaded)';
                }
            } else {
                selectedFilesText.textContent = 'No images selected';
            }
        }
        
        function updateFileList(indexToRemove) {
            // Create a new FileList (since we can't directly modify the existing one)
            const dt = new DataTransfer();
            const files = imageInput.files;
            
            for (let i = 0; i < files.length; i++) {
                if (i !== indexToRemove) {
                    dt.items.add(files[i]);
                }
            }
            
            imageInput.files = dt.files;
            selectedFilesText.textContent = dt.files.length + ' image(s) selected';
        }
        
        // Click on upload area to trigger file input
        imageUploadArea.addEventListener('click', () => {
            imageInput.click();
        });
    </script>
</body>
</html>