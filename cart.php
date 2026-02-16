<?php
require_once 'db.php';

// Iniciar sesión si no está ya iniciada (necesario para la lógica del carrito)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Manejar Acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $productId = $_POST['product_id'];
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
        } else {
            $_SESSION['cart'][$productId] = 1;
        }

        if (isset($_POST['ajax'])) {
            $count = 0;
            foreach ($_SESSION['cart'] as $qty) {
                $count += $qty;
            }
            // Limpiar búfer para asegurar que no haya espacios en blanco/salida antes del JSON
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'cartCount' => $count, 'message' => 'Added to cart']);
            exit;
        }

        // Redirigir para prevenir reenvío del formulario
        header('Location: cart.php');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $productId = $_POST['product_id'];
        $quantity = intval($_POST['quantity']);
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        header('Location: cart.php');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $productId = $_POST['product_id'];
        unset($_SESSION['cart'][$productId]);
        header('Location: cart.php');
        exit;
    }
}

// Obtener Detalles del Carrito
$cartItems = [];
$total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $db = new Database();

    // INICIO: Forma segura de manejar cláusula IN
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    // FIN: Forma segura de manejar cláusula IN

    foreach ($products as $product) {
        if (isset($_SESSION['cart'][$product['id']])) {
            $product['quantity'] = $_SESSION['cart'][$product['id']];
            $product['subtotal'] = $product['price'] * $product['quantity'];
            $total += $product['subtotal'];
            $cartItems[] = $product;
        }
    }
}

// Incluir encabezado solo después de la lógica y posibles redirecciones/salidas AJAX
include 'header.php';
?>

<div class="container">
    <h1 class="text-center mt-2 mb-2" style="color: var(--primary-color);">Tu Carrito de Compras</h1>

    <?php if (empty($cartItems)): ?>
        <div class="text-center mb-2">
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">Tu carrito está vacío.</p>
            <a href="menu.php" class="btn">Ver Menú</a>
        </div>
        <!-- Espaciador para empujar el pie de página hacia abajo si el carrito está vacío -->
        <div style="height: 200px;"></div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="img"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </div>
                        </td>
                        <td>$
                            <?php echo number_format($item['price'], 2); ?>
                        </td>
                        <td>
                            <form action="cart.php" method="post" style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0"
                                    style="width: 60px; padding: 0.5rem; border-radius: 4px; border: 1px solid #444; background: #333; color: white;">
                                <button type="submit" class="btn-secondary"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Update</button>
                            </form>
                        </td>
                        <td>$
                            <?php echo number_format($item['subtotal'], 2); ?>
                        </td>
                        <td class="cart-actions">
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit"
                                    style="background: none; border: none; color: var(--accent-color); cursor: pointer; text-decoration: underline;">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-total">
            Total: $
            <?php echo number_format($total, 2); ?>
        </div>

        <div class="text-center mb-2" style="display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="menu.php" class="btn btn-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>