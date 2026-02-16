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
    <title>Sweet Bean</title>
    <!-- Fuentes -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap"
        rel="stylesheet">
    <!-- Estilos -->
    <link rel="stylesheet" href="style.css">
    <!-- Precarga -->
    <link rel="preload"
        href="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
        as="image">
</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">Sweet Bean</a>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="menu.php">Menú</a></li>
                    <li><a href="contact.php">Contacto</a></li>
                    <?php if (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'kevin'): ?>
                        <li><a href="add_product.php" style="color: var(--accent-color);">Añadir Producto</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li>
                            <a href="cart.php" class="cart-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <?php if ($cartCount > 0): ?>
                                    <span class="cart-count"><?php echo $cartCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li><span style="color: var(--primary-color);">Hola,
                                <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                        <li><a href="logout.php" class="btn btn-secondary"
                                style="padding: 0.5rem 1rem; font-size: 0.9rem;">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php" class="btn btn-secondary"
                                style="padding: 0.5rem 1rem; font-size: 0.9rem;">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>