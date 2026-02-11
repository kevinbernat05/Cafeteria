<?php include 'header.php'; ?>

<div class="container">
    <section class="hero">
        <div class="hero-content">
            <h1>Sweet Bean</h1>
            <p>Experimenta el arte del café en cada taza.</p>
            <a href="menu.php" class="btn">Ver Menu</a>
        </div>
    </section>

    <section class="featured-products mb-2">
        <h2 class="text-center mb-2" style="color: var(--primary-color);">Productos Destacados</h2>
        <div class="products-grid">
            <!-- Hardcoded featured items for display, or fetch from DB if available -->
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=500&auto=format&fit=crop&q=60"
                        alt="Espresso">
                </div>
                <div class="product-info">
                    <h3 class="product-title">Espresso</h3>
                    <p class="product-description">Nuestro espresso de mezcla de casa.</p>
                    <div class="product-footer">
                        <span class="price">$2.50</span>
                        <a href="menu.php" class="btn btn-secondary add-btn">Pide Ahora</a>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&auto=format&fit=crop&q=60"
                        alt="Cappuccino">
                </div>
                <div class="product-info">
                    <h3 class="product-title">Cappuccino</h3>
                    <p class="product-description">Perfectamente equilibrado, espresso, leche蒸し, y espuma.</p>
                    <div class="product-footer">
                        <span class="price">$3.50</span>
                        <a href="menu.php" class="btn btn-secondary add-btn">Pide Ahora</a>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500&auto=format&fit=crop&q=60"
                        alt="Croissant">
                </div>
                <div class="product-info">
                    <h3 class="product-title">Croissant</h3>
                    <p class="product-description">Croissant de mantequilla y mantequilla.</p>
                    <div class="product-footer">
                        <span class="price">$2.00</span>
                        <a href="menu.php" class="btn btn-secondary add-btn">Pide Ahora</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-us mt-2 mb-2 text-center">
        <h2 style="color: var(--primary-color);">Nuestra historia</h2>
        <p style="max-width: 800px; margin: 0 auto; color: var(--muted-text);">
            Sweet bean fué fundada en 2020 por Ana y Kevin, quienes tras años de viajar por Europa y Asia, decidieron
            abrir su primer cafetería en Madrid. Desde entonces, han estado comprometidos con ofrecer la mejor experiencia
            en cada taza de café. Su objetivo es que cada cliente se quede con una sonrisa en la cara y un café que le
            haga sentir como en casa.
        </p>
    </section>
</div>

<?php include 'footer.php'; ?>