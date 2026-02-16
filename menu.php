<?php
require_once 'db.php';
include 'header.php';

$db = new Database();
// Obtener productos
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
            // Caso por defecto
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
                    <button type="button" class="btn btn-secondary add-btn open-modal-btn"
                        data-product='<?php echo htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8'); ?>'>
                        Add
                    </button>
                    <?php if (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'kevin'): ?>
                        <form action="delete_product.php" method="POST" style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-secondary"
                                style="padding: 0.5rem 1rem; font-size: 0.9rem; border-color: var(--accent-color); color: var(--accent-color);">
                                Delete
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <!-- Sección Cafés -->
    <section class="menu-section">
        <h2 class="section-title">Cafés</h2>
        <div class="carousel-container">
            <?php foreach ($cafes as $product)
                renderProductCard($product); ?>
        </div>
    </section>

    <!-- Sección Comidas -->
    <section class="menu-section">
        <h2 class="section-title">Comidas</h2>
        <div class="carousel-container">
            <?php foreach ($comidas as $product)
                renderProductCard($product); ?>
        </div>
    </section>

    <!-- Sección Bebidas -->
    <section class="menu-section">
        <h2 class="section-title">Bebidas</h2>
        <div class="carousel-container">
            <?php foreach ($bebidas as $product)
                renderProductCard($product); ?>
        </div>
    </section>
</div>
</div>





<div id="toast-container"></div>
<!-- Estructura del Modal del Carrito -->
<div class="modal-overlay" id="cartModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <div class="modal-body">
            <img src="" alt="Product" class="modal-product-image" id="modalImage">
            <h3 class="modal-title" id="modalTitle">Product Name</h3>
            <p id="modalDescription" style="color: var(--muted-text); margin-bottom: 1rem;">Description</p>
            <div class="modal-price" id="modalPrice">$0.00</div>

            <input type="hidden" id="modalProductId">

            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn" onclick="addToCart()">Add to Cart</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Lógica del Deslizador de Carrusel
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
                const walk = (x - startX) * 2;
                slider.scrollLeft = scrollLeft - walk;
            });
        }); // Corchetes de cierre arreglados aquí

        // 2. Agregar escuchas de eventos para botones modales
        document.querySelectorAll('.open-modal-btn').forEach(button => {
            button.addEventListener('click', function () {
                try {
                    const productData = JSON.parse(this.dataset.product);
                    openModal(productData);
                } catch (e) {
                    console.error("Error parsing product data", e);
                }
            });
        });
    }); // Fin de DOMContentLoaded

    // 3. Funciones del Modal (Mantenerlas fuera o dentro, pero fuera está bien)
    const modalOverlay = document.getElementById('cartModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalPrice = document.getElementById('modalPrice');
    const modalProductId = document.getElementById('modalProductId');

    function openModal(product) {
        modalImage.src = product.image;
        modalTitle.textContent = product.name;
        modalDescription.textContent = product.description;
        modalPrice.textContent = '$' + parseFloat(product.price).toFixed(2);
        modalProductId.value = product.id;
        modalOverlay.classList.add('active');
    }

    function closeModal() {
        modalOverlay.classList.remove('active');
    }

    // Cerrar al hacer clic fuera
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    function addToCart() {
        const productId = modalProductId.value;
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('product_id', productId);
        formData.append('ajax', 'true');

        fetch('cart.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(el => el.textContent = data.cartCount);


                    closeModal();
                    showToast('Product added to cart!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add product to cart.', 'error');
            });
    }

    // Función Toast
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        toast.innerHTML = `
        <span class="toast-message">${message}</span>
        <button class="toast-close">&times;</button>
    `;

        container.appendChild(toast);

        // Eliminación automática
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in forwards';
            toast.addEventListener('animationend', () => {
                toast.remove();
            });
        }, 3000);

        // Clic para eliminar
        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.style.animation = 'slideOut 0.3s ease-in forwards';
            toast.addEventListener('animationend', () => {
                toast.remove();
            });
        });
    }
</script>

<?php include 'footer.php'; ?>