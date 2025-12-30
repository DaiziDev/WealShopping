<?php
// customer/profile.php
require_once 'auth_check.php';

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $zip_code = sanitize($_POST['zip_code']);
    $country = sanitize($_POST['country']);

    // Validation
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // Check if email is already taken by another user
    if ($email !== $current_user['email']) {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $current_user['id']]);
        if ($stmt->fetch()) {
            $errors[] = "Email is already registered";
        }
    }

    // Update profile if no errors
    if (empty($errors)) {
        $sql = "UPDATE users SET 
                first_name = ?, 
                last_name = ?, 
                email = ?, 
                phone = ?, 
                address = ?, 
                city = ?, 
                state = ?, 
                zip_code = ?, 
                country = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $first_name,
            $last_name,
            $email,
            $phone,
            $address,
            $city,
            $state,
            $zip_code,
            $country,
            $current_user['id']
        ]);
        
        // Update session
        $_SESSION['email'] = $email;
        
        $success = "Profile updated successfully!";
        
        // Refresh current user data
        $current_user = getUserById($_SESSION['user_id']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/customer.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="customer-container">
        <div class="customer-layout">
            <?php include 'sidebar.php'; ?>
            
            <main class="customer-main">
                <!-- Profile Header -->
                <div class="customer-header">
                    <h1>My Profile</h1>
                    <p>Manage your personal information</p>
                </div>

                <!-- Display messages -->
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <div class="section-card">
                    <form method="POST" action="" class="profile-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($current_user['first_name']); ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($current_user['last_name']); ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($current_user['email']); ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($current_user['phone']); ?>">
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($current_user['address']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($current_user['city']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="state">State/Region</label>
                                <input type="text" id="state" name="state" 
                                       value="<?php echo htmlspecialchars($current_user['state']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="zip_code">Zip/Postal Code</label>
                                <input type="text" id="zip_code" name="zip_code" 
                                       value="<?php echo htmlspecialchars($current_user['zip_code']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" 
                                       value="<?php echo htmlspecialchars($current_user['country']); ?>">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="change-password.php" class="btn btn-secondary">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Account Information -->
                <div class="section-card">
                    <h3>Account Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Username</span>
                            <span class="info-value"><?php echo htmlspecialchars($current_user['username']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Account Type</span>
                            <span class="info-value"><?php echo ucfirst($current_user['role']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Member Since</span>
                            <span class="info-value"><?php echo date('F d, Y', strtotime($current_user['created_at'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value"><?php echo date('F d, Y', strtotime($current_user['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>