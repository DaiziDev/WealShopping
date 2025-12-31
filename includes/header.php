<?php
require_once 'config.php';

if (!isset($pdo)) {
    require_once 'config.php';
}

// Get current user if logged in
if (isLoggedIn()) {
    $current_user = getUserById($_SESSION['user_id']);
}

// Get current page and query parameters
$current_page = basename($_SERVER['PHP_SELF']);
$current_category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
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
    <link rel="stylesheet" href="<?php echo asset_url('css/customer.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
    /* ===== DROPDOWN FIXES ===== */
    .dropdown {
        position: relative;
    }

    .dropdown-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        text-decoration: none !important;
    }

    .dropdown-toggle::after {
        content: '▾';
        margin-left: 5px;
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        min-width: 220px;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border: 1px solid #eaeaea;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        padding: 10px 0;
        margin-top: 10px;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        white-space: nowrap;
    }

    .dropdown-item:hover {
        background-color: rgba(255, 77, 109, 0.1);
        color: #ff4d6d;
    }

    .dropdown-item i {
        width: 18px;
        text-align: center;
    }

    .dropdown-divider {
        height: 1px;
        background-color: #eaeaea;
        margin: 10px 0;
    }

    /* ===== MOBILE MENU FIXES ===== */
    .mobile-menu {
        display: none;
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 20px;
        z-index: 999;
        max-height: calc(100vh - 70px);
        overflow-y: auto;
    }

    .mobile-menu.active {
        display: block;
    }

    .mobile-submenu {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .mobile-account-link {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 0;
        color: #333;
        text-decoration: none;
        font-weight: 500;
    }

    .mobile-submenu-toggle {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        padding: 12px 15px;
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .mobile-submenu-toggle.open {
        transform: rotate(180deg);
    }

    .mobile-submenu-items {
        display: none;
        width: 100%;
        padding-left: 30px;
        list-style: none;
        margin: 5px 0 10px 0;
        background: rgba(0,0,0,0.02);
        border-radius: 8px;
    }

    .mobile-submenu-items.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mobile-submenu-items li {
        margin: 0;
    }

    .mobile-submenu-items a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .mobile-submenu-items a:hover,
    .mobile-submenu-items a.active {
        background: rgba(255, 77, 109, 0.05);
        color: #ff4d6d;
        border-left-color: #ff4d6d;
    }

    .mobile-submenu-items i {
        width: 18px;
        text-align: center;
    }

    /* ===== NAVBAR STYLES ===== */
    .navbar.scrolled {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .mobile-only {
        display: none;
    }

    @media (max-width: 768px) {
        .mobile-only {
            display: flex;
        }
        
        .nav-menu .dropdown {
            display: none;
        }
    }

    /* Search bar */
    .search-bar {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: white;
        padding: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        display: none;
        z-index: 1000;
        align-items: center;
        gap: 10px;
    }

    .search-bar.active {
        display: flex;
    }

    .search-bar form {
        display: flex;
        width: 100%;
        gap: 10px;
    }

    .search-bar input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #eaeaea;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-bar button {
        padding: 10px 20px;
        background: #ff4d6d;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .search-close {
        background: none;
        border: none;
        font-size: 18px;
        color: #666;
        cursor: pointer;
        padding: 5px;
    }

    /* Cart count */
    .cart-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ff4d6d;
        color: white;
        font-size: 11px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .cart-count:empty {
        display: none;
    }
    </style>
</head>
    <!-- SIMPLE DROPDOWN FIX -->
    <script>
    // Wait for everything to load
    window.addEventListener('load', function() {
        console.log('Page loaded, fixing dropdown...');
        
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        
        if (userDropdown && dropdownMenu) {
            console.log('Found dropdown elements');
            
            // REMOVE ALL EXISTING CLICK LISTENERS
            const newUserDropdown = userDropdown.cloneNode(true);
            userDropdown.parentNode.replaceChild(newUserDropdown, userDropdown);
            
            // ADD NEW SIMPLE LISTENER
            newUserDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                console.log('Dropdown clicked, toggling...');
                
                // Toggle the dropdown
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    console.log('Dropdown hidden');
                } else {
                    dropdownMenu.classList.add('show');
                    console.log('Dropdown shown');
                }
            });
            
            // Close when clicking elsewhere
            document.addEventListener('click', function(e) {
                if (dropdownMenu.classList.contains('show') && 
                    !dropdownMenu.contains(e.target) && 
                    !newUserDropdown.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    console.log('Dropdown closed from outside click');
                }
            });
            
            console.log('Dropdown fix applied successfully');
        } else {
            console.log('Dropdown elements not found:', {userDropdown, dropdownMenu});
        }
    });
    </script>
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
                <img style="width: 100px; height: auto;" src="<?php echo asset_url('images/WealShopping.png'); ?>" alt="WealShopping Logo">
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
                
                <!-- About link -->
                <li class="nav-item">
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#about" class="nav-link">About</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#about'); ?>" class="nav-link">About</a>
                    <?php endif; ?>
                </li>
                
                <!-- Contact link -->
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
                            <a href="<?php echo site_url('admin/'); ?>" class="nav-link admin-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/') !== false) ? 'active' : ''; ?>">
                                <i class="fas fa-cog"></i> Admin Panel
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown">
                            <i class="fas fa-user-circle"></i>
                            <?php 
                            $display_name = !empty($current_user['first_name']) ? $current_user['first_name'] : $current_user['username'];
                            echo htmlspecialchars($display_name); 
                            ?>
                        </a>
                        <div class="dropdown-menu" id="userDropdownMenu">
                            <?php if (isAdmin()): ?>
                                <a class="dropdown-item" href="<?php echo site_url('admin/index.php'); ?>">
                                    <i class="fas fa-cog"></i> Admin Panel
                                </a>
                            <?php else: ?>
                                <a class="dropdown-item" href="<?php echo site_url('customer/dashboard.php'); ?>">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            <?php endif; ?>
                            <a class="dropdown-item" href="<?php echo site_url('customer/orders.php'); ?>">
                                <i class="fas fa-shopping-bag"></i> My Orders
                            </a>
                            <a class="dropdown-item" href="<?php echo site_url('customer/profile.php'); ?>">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <?php if (!isAdmin()): ?>
                                <a class="dropdown-item" href="<?php echo site_url('customer/wishlist.php'); ?>">
                                    <i class="fas fa-heart"></i> Wishlist
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo site_url('customer/logout.php'); ?>">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                    
                <?php else: ?>
                    <!-- Login/Register links -->
                    <li class="nav-item">
                        <a href="<?php echo page_url('login.php'); ?>" 
                           class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo page_url('register.php'); ?>" 
                           class="nav-link <?php echo ($current_page == 'register.php') ? 'active' : ''; ?>">
                            <i class="fas fa-user-plus"></i> Register
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
                
                <?php if (!isLoggedIn()): ?>
                    <a href="<?php echo page_url('login.php'); ?>" class="icon-btn user-btn mobile-only">
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
                    <button type="button" class="search-close" id="searchClose"><i class="fas fa-times"></i></button>
                </form>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <ul class="mobile-nav">
                <li>
                    <a href="<?php echo site_url('index.php'); ?>" 
                       class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                
                <!-- Mobile Shop Categories -->
                <li>
                    <a href="<?php echo page_url('shop.php'); ?>" 
                       class="<?php echo ($is_shop_page && ($current_category == '' || $current_category == 'clothing' || $current_category == 'Clothing')) ? 'active' : ''; ?>">
                        <i class="fas fa-tshirt"></i> Clothing
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo page_url('shop.php?category=footwear'); ?>" 
                       class="<?php echo ($is_shop_page && $current_category == 'footwear') ? 'active' : ''; ?>">
                        <i class="fas fa-shoe-prints"></i> Shoes
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo page_url('shop.php?category=accessories'); ?>" 
                       class="<?php echo ($is_shop_page && $current_category == 'accessories') ? 'active' : ''; ?>">
                        <i class="fas fa-glasses"></i> Accessories
                    </a>
                </li>
                
                <!-- Mobile About & Contact -->
                <li>
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#about"><i class="fas fa-info-circle"></i> About</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#about'); ?>"><i class="fas fa-info-circle"></i> About</a>
                    <?php endif; ?>
                </li>
                
                <li>
                    <?php if ($current_page == 'index.php'): ?>
                        <a href="#contact"><i class="fas fa-envelope"></i> Contact</a>
                    <?php else: ?>
                        <a href="<?php echo site_url('index.php#contact'); ?>"><i class="fas fa-envelope"></i> Contact</a>
                    <?php endif; ?>
                </li>
                
                <!-- Mobile User Links -->
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li>
                            <a href="<?php echo site_url('admin/index.php'); ?>" class="admin-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/') !== false) ? 'active' : ''; ?>">
                                <i class="fas fa-cog"></i> Admin Panel
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Customer Panel for Mobile -->
                        <li class="mobile-submenu">
                            <a href="<?php echo site_url('customer/dashboard.php'); ?>" class="mobile-account-link">
                                <i class="fas fa-user-circle"></i> My Account
                            </a>
                            <button class="mobile-submenu-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <ul class="mobile-submenu-items">
                                <li>
                                    <a href="<?php echo site_url('customer/dashboard.php'); ?>">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('customer/orders.php'); ?>">
                                        <i class="fas fa-shopping-bag"></i> My Orders
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('customer/profile.php'); ?>">
                                        <i class="fas fa-user"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('customer/wishlist.php'); ?>">
                                        <i class="fas fa-heart"></i> Wishlist
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <li>
                        <a href="<?php echo site_url('customer/logout.php'); ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Login/Register for mobile -->
                    <li>
                        <a href="<?php echo page_url('login.php'); ?>" 
                           class="<?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo page_url('register.php'); ?>" 
                           class="<?php echo ($current_page == 'register.php') ? 'active' : ''; ?>">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

       <!-- JavaScript -->
    <script>
    // Preloader
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            setTimeout(() => {
                preloader.classList.add('hidden');
            }, 1000);
        }

        // ===== SIMPLE DROPDOWN FIX =====
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        
        if (userDropdown && dropdownMenu) {
            // CLICK TO TOGGLE DROPDOWN
            userDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });
            
            // CLICK INSIDE DROPDOWN TO CLOSE WHEN CLICKING LINKS
            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.closest('.dropdown-item')) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }

        // ===== MOBILE MENU TOGGLE =====
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                menuToggle.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                
                // Close dropdown when opening mobile menu
                dropdownMenu?.classList.remove('show');
                
                // Toggle body scroll
                document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            });
            
            // Close mobile menu when clicking links
            mobileMenu.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' && !e.target.classList.contains('mobile-submenu-toggle')) {
                    mobileMenu.classList.remove('active');
                    menuToggle.classList.remove('active');
                    document.body.style.overflow = '';
                    
                    // Close submenus
                    document.querySelectorAll('.mobile-submenu-items.show').forEach(menu => {
                        menu.classList.remove('show');
                        menu.previousElementSibling?.classList.remove('open');
                    });
                }
            });
        }

        // ===== SEARCH TOGGLE =====
        const searchToggle = document.getElementById('searchToggle');
        const searchBar = document.querySelector('.search-bar');
        const searchClose = document.getElementById('searchClose');
        
        if (searchToggle && searchBar) {
            searchToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                searchBar.classList.toggle('active');
                if (searchBar.classList.contains('active')) {
                    searchBar.querySelector('input').focus();
                }
            });
        }
        
        if (searchClose && searchBar) {
            searchClose.addEventListener('click', function() {
                searchBar.classList.remove('active');
            });
        }
        
        // ===== MOBILE SUBMENU TOGGLE =====
        const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
        mobileSubmenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const submenu = this.closest('.mobile-submenu').querySelector('.mobile-submenu-items');
                const isOpen = this.classList.contains('open');
                
                // Close other open submenus
                if (!isOpen) {
                    document.querySelectorAll('.mobile-submenu-items.show').forEach(openMenu => {
                        if (openMenu !== submenu) {
                            openMenu.classList.remove('show');
                            openMenu.previousElementSibling?.classList.remove('open');
                        }
                    });
                }
                
                // Toggle current submenu
                submenu.classList.toggle('show');
                this.classList.toggle('open');
            });
        });

        // ===== CLOSE ALL MENUS WHEN CLICKING OUTSIDE =====
        document.addEventListener('click', function(e) {
            // Close dropdown if clicking outside
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                if (!dropdownMenu.contains(e.target) && !userDropdown?.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            }
            
            // Close search bar if clicking outside
            if (searchBar && searchBar.classList.contains('active')) {
                if (!searchBar.contains(e.target) && !searchToggle.contains(e.target)) {
                    searchBar.classList.remove('active');
                }
            }
            
            // Close mobile menu if clicking outside
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                    mobileMenu.classList.remove('active');
                    menuToggle.classList.remove('active');
                    document.body.style.overflow = '';
                    
                    // Close mobile submenus
                    document.querySelectorAll('.mobile-submenu-items.show').forEach(menu => {
                        menu.classList.remove('show');
                        menu.previousElementSibling?.classList.remove('open');
                    });
                }
            }
        });

        // ===== CLOSE MENUS ON ESCAPE KEY =====
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchBar?.classList.remove('active');
                dropdownMenu?.classList.remove('show');
                
                if (mobileMenu?.classList.contains('active')) {
                    mobileMenu.classList.remove('active');
                    menuToggle.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });
    });

    // ===== NAVBAR SCROLL EFFECT =====
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    </script>
</body>
</html>