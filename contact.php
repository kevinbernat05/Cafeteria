<?php
include 'header.php';

$messageSent = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // En una aplicación real, enviarías un correo electrónico o guardarías en la BD aquí.
    // Por ahora, solo mostramos un mensaje de éxito.
    $messageSent = true;
}
?>

<div class="container">
    <section class="contact-header text-center mt-2 mb-2">
        <h1 style="color: var(--primary-color);">Contacta con nosotros</h1>
        <p style="color: var(--muted-text);">Nos encantaría escuchar de ti. Envíanos un mensaje!</p>
    </section>

    <div class="contact-form-container text-center mb-2">
        <?php if ($messageSent): ?>
            <div
                style="background-color: rgba(42, 157, 143, 0.2); border: 1px solid var(--success-color); color: var(--success-color); padding: 1rem; border-radius: var(--border-radius); max-width: 600px; margin: 0 auto;">
                <strong>¡Mensaje Enviado!</strong> Gracias por contactarnos. Te contactaremos pronto.
            </div>
        <?php else: ?>
            <form action="contact.php" method="post" style="max-width: 600px; margin: 0 auto; text-align: left;">
                <div class="mb-2">
                    <label for="name"
                        style="display: block; margin-bottom: 0.5rem; color: var(--secondary-color);">Nombre</label>
                    <input type="text" id="name" name="name" required
                        style="width: 100%; padding: 0.75rem; border-radius: var(--border-radius); border: 1px solid #444; background: var(--surface-color); color: white;">
                </div>

                <div class="mb-2">
                    <label for="email"
                        style="display: block; margin-bottom: 0.5rem; color: var(--secondary-color);">Email</label>
                    <input type="email" id="email" name="email" required
                        style="width: 100%; padding: 0.75rem; border-radius: var(--border-radius); border: 1px solid #444; background: var(--surface-color); color: white;">
                </div>

                <div class="mb-2">
                    <label for="message"
                        style="display: block; margin-bottom: 0.5rem; color: var(--secondary-color);">Mensaje</label>
                    <textarea id="message" name="message" rows="5" required
                        style="width: 100%; padding: 0.75rem; border-radius: var(--border-radius); border: 1px solid #444; background: var(--surface-color); color: white;"></textarea>
                </div>

                <button type="submit" class="btn" style="width: 100%;">Enviar Mensaje</button>
            </form>
        <?php endif; ?>
    </div>

    <section class="contact-info text-center mt-2 mb-2">
        <h3 style="color: var(--primary-color);">Visit Us</h3>
        <p style="color: var(--muted-text);">
            123 Coffee Lane<br>
            Brew City, BC 12345<br>
            (555) 123-4567
        </p>
    </section>
</div>

<?php include 'footer.php'; ?>