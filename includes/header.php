<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Toko Baju Online</title>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <a href="<?php echo baseUrl(); ?>" class="logo">
                    <i class="fas fa-tshirt"></i> TokoKaos
                </a>
                
                <div class="nav-menu" id="navMenu">
                    <a href="<?php echo baseUrl(); ?>">Home</a>
                    <a href="<?php echo baseUrl('products.php'); ?>">Produk</a>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <a href="<?php echo baseUrl('admin/dashboard.php'); ?>">Dashboard Admin</a>
                        <?php else: ?>
                            <a href="<?php echo baseUrl('cart.php'); ?>">
                                <i class="fas fa-shopping-cart"></i> Keranjang
                            </a>
                            <a href="<?php echo baseUrl('orders.php'); ?>">Pesanan Saya</a>
                        <?php endif; ?>
                        <a href="<?php echo baseUrl('logout.php'); ?>" class="btn-logout">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo baseUrl('login.php'); ?>" class="btn-login">Login</a>
                        <a href="<?php echo baseUrl('register.php'); ?>" class="btn-register">Register</a>
                    <?php endif; ?>
                </div>
                
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>
    
    <main>
