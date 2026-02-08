<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cartCount += $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso Elegance</title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap"
        rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="style.css">
    <!-- Preload -->
    <link rel="preload"
        href="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
        as="image">
</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">Cafeteria monisima de la muerte</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.php">Menu</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li>
                        <a href="cart.php" class="cart-icon">
                            Cart
                            <?php if ($cartCount > 0): ?>
                                <span class="cart-count"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>