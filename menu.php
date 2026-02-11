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
        <h1 style="color: var(--primary-color);">Nuestro menú</h1>
        <p style="color: var(--muted-text);">Selección cuidadosa de nuestros mejores cafes y dulces.</p>
    </section>

    <?php
    $cafes = [];
    $comidas = [];
    $bebidas = [];

    foreach ($products as $product) {
        if ($product['category'] === 'Hot Coffee') {
            $cafes[] = $product;
        } elseif ($product['category'] === 'Bakery') {
            $comidas[] = $product;
        } elseif ($product['category'] === 'Cold Coffee') {
            $bebidas[] = $product;
        } else {
            // Fallback
            $bebidas[] = $product;
        }
    }

    function renderProductCard($product)
    {
        ?>
        <div class="product-card carousel-card">
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
                        <button type="submit" class="btn btn-secondary add-btn">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <!-- Cafés Section -->
    <section class="menu-section">
        <h2 class="section-title">Cafés</h2>
        <div class="carousel-container">
            <?php foreach ($cafes as $product)
                renderProductCard($product); ?>
        </div>
    </section>

    <!-- Comidas Section -->
    <section class="menu-section">
        <h2 class="section-title">Comidas</h2>
        <div class="carousel-container">
            <?php foreach ($comidas as $product)
                renderProductCard($product); ?>
        </div>
    </section>

    <!-- Bebidas Section -->
    <section class="menu-section">
        <h2 class="section-title">Bebidas</h2>
        <div class="carousel-container">
            <?php foreach ($bebidas as $product)
                renderProductCard($product); ?>
        </div>
    </section>
</div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sliders = document.querySelectorAll('.carousel-container');
        let isDown = false;
        let startX;
        let scrollLeft;

        sliders.forEach(slider => {
            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('active');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 2; // scroll-fast
                slider.scrollLeft = scrollLeft - walk;
            });
        });
    });
</script>

<?php include 'footer.php'; ?>