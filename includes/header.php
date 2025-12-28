<?php
require_once 'config.php';

if (!isset($pdo)) {
    require_once 'config.php';
}
// Get current page and query parameters
$current_page = basename($_SERVER['PHP_SELF']);
$current_category = isset($_GET['category']) ? $_GET['category'] : '';
$is_shop_page = ($current_page == 'shop.php');
$is_auth_page = ($current_page == 'login.php' || $current_page == 'register.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo SITE_NAME; ?> | Premium Fashion Store</title>
    <meta name="description" content="Premium fashion clothing, shoes and accessories for men, women and children in Cameroon">
    <meta name="keywords" content="fashion, clothing, shoes, accessories, Cameroon, Douala, Yaounde">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset_url('images/WealShopping.png'); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset_url('css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/animations.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/responsiveness.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
            <div class="loader-square"></div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <!-- Logo -->
            <a href="<?php echo site_url('index.php'); ?>" class="logo">
                <!-- <span class="logo-text">Weal</span><span class="logo-accent">Shopping</span> -->
                 <img style="width: 100px; height: auto;" src="../../fashion-shop/assets/images/WealShopping.png" alt="">
            </a>
            
            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo site_url('index.php'); ?>" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        Home
                    </a>
                </li>
                
                <!-- Shop Categories -->
                <li class="nav-item">
                    <a href="<?php echo page_url('shop.php'); ?>" 
                       class="nav-link <?php echo ($is_shop_page && ($current_category == '' || $current_category == 'clothing' || $current_category == 'Clothing')) ? 'active' : ''; ?>">
                        Clothing
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?php echo page_url('shop.php?category=footwear'); ?>" 
                       class="nav-link <?php echo ($is_shop_page && $current_category == 'footwear') ? 'active' : ''; ?>">
                        Shoes
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?php echo page_url('shop.php?category=accessories'); ?>" 
                       class="nav-link <?php echo ($is_shop_page && $current_category == 'accessories') ? 'active' : ''; ?>">
                        Accessories
                    </a>
                </li>
                
                <!-- About link - fixed to work properly -->
                <li class="nav-item">
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#about" class="nav-link">About</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#about'); ?>" class="nav-link">About</a>
                    <?php endif; ?>
                </li>
                
                <!-- Contact link - fixed to work properly -->
                <li class="nav-item">
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#contact" class="nav-link">Contact</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#contact'); ?>" class="nav-link">Contact</a>
                    <?php endif; ?>
                </li>
                
                <!-- User Links -->
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a href="<?php echo site_url('admin/'); ?>" class="nav-link admin-link">
                                Admin Panel
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="<?php echo page_url('account.php'); ?>" 
                           class="nav-link <?php echo ($current_page == 'account.php') ? 'active' : ''; ?>">
                            My Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url('includes/logout.php'); ?>" class="nav-link">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?php echo page_url('login.php'); ?>" 
                           class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo page_url('register.php'); ?>" 
                           class="nav-link <?php echo ($current_page == 'register.php') ? 'active' : ''; ?>">
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <!-- Icons -->
            <div class="nav-icons">
                <button class="icon-btn search-btn" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                <a href="<?php echo page_url('cart.php'); ?>" class="icon-btn cart-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <?php 
                    $cart_count = getCartCount();
                    if ($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo page_url('account.php'); ?>" class="icon-btn user-btn">
                        <i class="fas fa-user"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo page_url('login.php'); ?>" class="icon-btn user-btn">
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
                <form action="<?php echo page_url('shop.php'); ?>" method="GET">
                    <input type="text" name="search" placeholder="Search for products, brands, and more..." required>
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    <button type="button" class="search-close"><i class="fas fa-times"></i></button>
                </form>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <ul class="mobile-nav">
                <li>
                    <a href="<?php echo site_url('index.php'); ?>" 
                       class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        Home
                    </a>
                </li>
                
                <!-- Mobile Shop Categories -->
                <li>
                    <a href="<?php echo page_url('shop.php'); ?>" 
                       class="<?php echo ($is_shop_page && ($current_category == '' || $current_category == 'clothing' || $current_category == 'Clothing')) ? 'active' : ''; ?>">
                        Clothing
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo page_url('shop.php?category=footwear'); ?>" 
                       class="<?php echo ($is_shop_page && $current_category == 'footwear') ? 'active' : ''; ?>">
                        Shoes
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo page_url('shop.php?category=accessories'); ?>" 
                       class="<?php echo ($is_shop_page && $current_category == 'accessories') ? 'active' : ''; ?>">
                        Accessories
                    </a>
                </li>
                
                <!-- Mobile About & Contact Links -->
                <li>
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#about">About</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#about'); ?>">About</a>
                    <?php endif; ?>
                </li>
                
                <li>
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#contact">Contact</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#contact'); ?>">Contact</a>
                    <?php endif; ?>
                </li>
                
                <!-- Mobile User Links -->
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="<?php echo site_url('admin/'); ?>" class="admin-link">Admin Panel</a></li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo page_url('account.php'); ?>" 
                           class="<?php echo ($current_page == 'account.php') ? 'active' : ''; ?>">
                            My Account
                        </a>
                    </li>
                    <li><a href="<?php echo site_url('includes/logout.php'); ?>">Logout</a></li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo page_url('login.php'); ?>" 
                           class="<?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo page_url('register.php'); ?>" 
                           class="<?php echo ($current_page == 'register.php') ? 'active' : ''; ?>">
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>