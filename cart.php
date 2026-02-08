<?php
require_once 'db.php';
include 'header.php';

// Handle Actions
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
        // Redirect to prevent form resubmission
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

// Fetch Cart Details
$cartItems = [];
$total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $db = new Database();
    $ids = implode(',', array_keys($_SESSION['cart']));
    // Warning: Direct interpolation of IDs is risky without sanitization if keys were not integers. 
    // Since they come from DB IDs which are ints, and we trust our internal logic (mostly), 
    // but for extra safety let's assume keys are safe or use prepared statement with IN clause (complex to bind dynamic array).
    // For this demo, valid integer keys are assumed.

    // Better approach:
    $placeholders = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['quantity'];
        $total += $product['subtotal'];
        $cartItems[] = $product;
    }
}
?>

<div class="container">
    <h1 class="text-center mt-2 mb-2" style="color: var(--primary-color);">Your Shopping Cart</h1>

    <?php if (empty($cartItems)): ?>
        <div class="text-center mb-2">
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">Your cart is empty.</p>
            <a href="menu.php" class="btn">Browse Menu</a>
        </div>
        <!-- Spacer to push footer down if cart is empty -->
        <div style="height: 200px;"></div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
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