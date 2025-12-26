<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header('Location: ' . SITE_URL);
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        
        header('Location: ' . SITE_URL);
        exit();
    } else {
        $error = 'Invalid email or password';
    }
}

require_once '../includes/header.php';
?>

<section class="auth-section section-padding">
    <div class="container">
        <div class="auth-container">
            <div class="auth-image" style="background-image: url('../assets/images/model-career-kit-still-life.jpg');"></div>
            <div class="auth-form">
                <h2>Welcome Back</h2>
                <p class="auth-subtitle">Sign in to your account</p>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </form>
                
                <div class="auth-links">
                    <a href="register.php">Don't have an account? Sign up</a>
                    <a href="forgot-password.php">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>