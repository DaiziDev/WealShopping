<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> | Premium Fashion Store</title>
    <meta name="description" content="Premium fashion clothing, shoes and accessories for men, women and children in Cameroon">
    <meta name="keywords" content="fashion, clothing, shoes, accessories, Cameroon, Douala, Yaounde">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>assets/images/WealShopping.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/animations.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Preloader -->
    <!-- <div class="preloader">
        <div class="loader">
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
        </div>
    </div> -->

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <!-- Logo -->
            <a href="<?php echo SITE_URL; ?>index.php" class="logo">
                <span class="logo-text">Weal</span><span class="logo-accent">Shopping</span>
            </a>
            
            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo SITE_URL; ?>index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li class="nav-item"><a href="<?php echo SITE_URL; ?>pages/shop.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">Clothing</a></li>
                <li class="nav-item"><a href="<?php echo SITE_URL; ?>pages/shop.php?category=footwear" class="nav-link <?php echo isset($_GET['category']) && $_GET['category'] == 'footwear' ? 'active' : ''; ?>">Shoes</a></li>
                <li class="nav-item"><a href="<?php echo SITE_URL; ?>pages/shop.php?category=accessories" class="nav-link <?php echo isset($_GET['category']) && $_GET['category'] == 'accessories' ? 'active' : ''; ?>">Accessories</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>admin/" class="nav-link admin-link">Admin Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="<?php echo SITE_URL; ?>pages/account.php" class="nav-link">My Account</a></li>
                    <li class="nav-item"><a href="<?php echo SITE_URL; ?>includes/logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="<?php echo SITE_URL; ?>pages/login.php" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="<?php echo SITE_URL; ?>pages/register.php" class="nav-link">Register</a></li>
                <?php endif; ?>
            </ul>
            
            <!-- Icons -->
            <div class="nav-icons">
                <button class="icon-btn search-btn" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                <a href="<?php echo SITE_URL; ?>pages/cart.php" class="icon-btn cart-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <?php 
                    $cart_count = getCartCount();
                    if ($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>pages/account.php" class="icon-btn user-btn">
                        <i class="fas fa-user"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>pages/login.php" class="icon-btn user-btn">
                        <i class="fas fa-user"></i>
                    </a>
                <?php endif; ?>
                <button class="menu-toggle" id="menuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <form action="<?php echo SITE_URL; ?>pages/shop.php" method="GET">
                    <input type="text" name="search" placeholder="Search for products, brands, and more..." required>
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    <button type="button" class="search-close"><i class="fas fa-times"></i></button>
                </form>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <ul class="mobile-nav">
                <li><a href="<?php echo SITE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>pages/shop.php">Clothing</a></li>
                <li><a href="<?php echo SITE_URL; ?>pages/shop.php?category=footwear">Shoes</a></li>
                <li><a href="<?php echo SITE_URL; ?>pages/shop.php?category=accessories">Accessories</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="<?php echo SITE_URL; ?>admin/" class="admin-link">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo SITE_URL; ?>pages/account.php">My Account</a></li>
                    <li><a href="<?php echo SITE_URL; ?>includes/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo SITE_URL; ?>pages/login.php">Login</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>