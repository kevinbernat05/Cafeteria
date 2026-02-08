<?php
require_once 'db.php';
include 'header.php';

$db = new Database();
// Fetch products
try {
    $stmt = $db->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    echo "<div class='container'><p class='error'>Error fetching products: " . $e->getMessage() . "</p></div>";
    $products = [];
}
?>

<div class="container">
    <section class="menu-header text-center mt-2 mb-2">
        <h1 style="color: var(--primary-color);">Our Menu</h1>
        <p style="color: var(--muted-text);">Carefully curated selection of fine coffees and pastries.</p>
    </section>

    <!-- Optional: Categories could go here -->

    <div class="products-grid mb-2">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <h3 class="product-title">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h3>
                    <p class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>
                    <div class="product-footer">
                        <span class="price">$
                            <?php echo number_format($product['price'], 2); ?>
                        </span>
                        <form action="cart.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn btn-secondary add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>