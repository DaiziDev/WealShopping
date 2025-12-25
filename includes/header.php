<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>EliteStyle | Premium Fashion Store</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <a href="../index.php" class="logo">
                <span class="logo-text">Elite</span><span class="logo-accent">Style</span>
            </a>
            
            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li class="nav-item"><a href="../index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li class="nav-item"><a href="shop.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">Clothing</a></li>
                <li class="nav-item"><a href="shop.php?category=shoes" class="nav-link <?php echo isset($_GET['category']) && $_GET['category'] == 'shoes' ? 'active' : ''; ?>">Shoes</a></li>
                <li class="nav-item"><a href="shop.php?category=accessories" class="nav-link <?php echo isset($_GET['category']) && $_GET['category'] == 'accessories' ? 'active' : ''; ?>">Accessories</a></li>
                <li class="nav-item"><a href="../index.php#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="../index.php#contact" class="nav-link">Contact</a></li>
            </ul>
            
            <!-- Icons -->
            <div class="nav-icons">
                <button class="icon-btn search-btn" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                <a href="cart.php" class="icon-btn cart-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                </a>
                <button class="icon-btn user-btn">
                    <i class="fas fa-user"></i>
                </button>
                <button class="menu-toggle" id="menuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <form action="shop.php" method="GET">
                    <input type="text" name="search" placeholder="Search for products, brands, and more...">
                    <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                    <button type="button" class="search-close"><i class="fas fa-times"></i></button>
                </form>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <ul class="mobile-nav">
                <li><a href="../index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="shop.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">Clothing</a></li>
                <li><a href="shop.php?category=shoes" class="<?php echo isset($_GET['category']) && $_GET['category'] == 'shoes' ? 'active' : ''; ?>">Shoes</a></li>
                <li><a href="shop.php?category=accessories" class="<?php echo isset($_GET['category']) && $_GET['category'] == 'accessories' ? 'active' : ''; ?>">Accessories</a></li>
                <li><a href="../index.php#about">About</a></li>
                <li><a href="../index.php#contact">Contact</a></li>
            </ul>
        </div>
    </nav>