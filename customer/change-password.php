<?php
// customer/change-password.php
require_once 'auth_check.php';

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($current_password)) {
        $errors[] = "Current password is required";
    }
    
    if (empty($new_password)) {
        $errors[] = "New password is required";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters";
    }
    
    if ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match";
    }
    
    // Verify current password
    if (empty($errors)) {
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$current_user['id']]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($current_password, $user['password'])) {
            $errors[] = "Current password is incorrect";
        }
    }
    
    // Update password if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashed_password, $current_user['id']]);
        
        $success = "Password changed successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - <?php echo SITE_NAME; ?></title>
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
                <!-- Change Password Header -->
                <div class="customer-header">
                    <h1>Change Password</h1>
                    <p>Update your account password</p>
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

                <!-- Password Form -->
                <div class="section-card">
                    <form method="POST" action="" class="password-form">
                        <div class="form-group">
                            <label for="current_password">Current Password *</label>
                            <div class="password-input">
                                <input type="password" id="current_password" name="current_password" required>
                                <button type="button" class="toggle-password" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password *</label>
                            <div class="password-input">
                                <input type="password" id="new_password" name="new_password" required>
                                <button type="button" class="toggle-password" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text">Minimum 6 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password *</label>
                            <div class="password-input">
                                <input type="password" id="confirm_password" name="confirm_password" required>
                                <button type="button" class="toggle-password" data-target="confirm_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                            <a href="profile.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Profile
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Password Tips -->
                <div class="section-card">
                    <h3>Password Tips</h3>
                    <ul class="tips-list">
                        <li><i class="fas fa-check-circle"></i> Use a combination of letters, numbers, and symbols</li>
                        <li><i class="fas fa-check-circle"></i> Avoid using personal information like birth dates</li>
                        <li><i class="fas fa-check-circle"></i> Don't reuse passwords from other websites</li>
                        <li><i class="fas fa-check-circle"></i> Consider using a password manager</li>
                    </ul>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.getElementById(target);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>