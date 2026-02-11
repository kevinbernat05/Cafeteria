<?php
require_once 'db.php';
include 'header.php';

$message = "";
$orderId = null;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $db = new Database();

    // Calculate total and prepare items for storage
    $total = 0;
    $items = [];

    // Fetch product details
    $placeholders = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        $items[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $qty,
            'subtotal' => $subtotal
        ];
    }

    // Insert Order
    try {
        $stmt = $db->prepare("INSERT INTO orders (customer_name, total_amount, items) VALUES (:name, :total, :items)");
        // For this demo, we'll use a placeholder name or "Guest" since we don't have auth
        $name = "Guest User";
        $itemsJson = json_encode($items);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':items', $itemsJson);
        $stmt->execute();

        // Clear Cart
        unset($_SESSION['cart']);
        $message = "Thank you for your order!";
    } catch (Exception $e) {
        $message = "Error processing your order: " . $e->getMessage();
    }
} else {
    $message = "Your cart is empty.";
}
?>

<div class="container text-center" style="padding: 4rem 0;">
    <?php if ($message === "Thank you for your order!"): ?>
        <h1 style="color: var(--success-color); margin-bottom: 2rem; font-size: 3rem;">Pedido Confirmada!</h1>
        <p style="font-size: 1.5rem; color: var(--text-color); margin-bottom: 2rem;">
            Tu café está siendo preparado. <br>
            Por favor, procede al mostrador cuando se llama tu nombre.
        </p>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    <?php else: ?>
        <h1 style="color: var(--accent-color); margin-bottom: 2rem;">Opps!</h1>
        <p style="font-size: 1.2rem; margin-bottom: 2rem;">
            <?php echo $message; ?>
        </p>
        <a href="menu.php" class="btn">Volver al Menu</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>