<?php include 'header.php'; ?>

<div class="container">
    <section class="hero">
        <div class="hero-content">
            <h1>Espresso Elegance</h1>
            <p>Experience the art of coffee in every cup.</p>
            <a href="menu.php" class="btn">View Menu</a>
        </div>
    </section>

    <section class="featured-products mb-2">
        <h2 class="text-center mb-2" style="color: var(--primary-color);">Featured Blends</h2>
        <div class="products-grid">
            <!-- Hardcoded featured items for display, or fetch from DB if available -->
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=500&auto=format&fit=crop&q=60"
                        alt="Espresso">
                </div>
                <div class="product-info">
                    <h3 class="product-title">Signature Espresso</h3>
                    <p class="product-description">Our bold and rich house blend espresso.</p>
                    <div class="product-footer">
                        <span class="price">$2.50</span>
                        <a href="menu.php" class="btn btn-secondary add-btn">Order Now</a>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&auto=format&fit=crop&q=60"
                        alt="Cappuccino">
                </div>
                <div class="product-info">
                    <h3 class="product-title">Classic Cappuccino</h3>
                    <p class="product-description">Perfectly balanced espresso, steamed milk, and foam.</p>
                    <div class="product-footer">
                        <span class="price">$3.50</span>
                        <a href="menu.php" class="btn btn-secondary add-btn">Order Now</a>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500&auto=format&fit=crop&q=60"
                        alt="Croissant">
                </div>
                <div class="product-info">
                    <h3 class="product-title">Butter Croissant</h3>
                    <p class="product-description">Flaky, buttery, and freshly baked every morning.</p>
                    <div class="product-footer">
                        <span class="price">$2.00</span>
                        <a href="menu.php" class="btn btn-secondary add-btn">Order Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-us mt-2 mb-2 text-center">
        <h2 style="color: var(--primary-color);">Our Story</h2>
        <p style="max-width: 800px; margin: 0 auto; color: var(--muted-text);">
            Founded in 2023, Espresso Elegance began with a simple mission: to serve the finest coffee in a warm and
            inviting atmosphere.
            We source our beans from sustainable farms and roast them to perfection. Whether you're here for a quick
            morning pick-me-up
            or a leisurely afternoon break, we promise a premium experience in every sip.
        </p>
    </section>
</div>

<?php include 'footer.php'; ?>