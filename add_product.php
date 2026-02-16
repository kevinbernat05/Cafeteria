<?php
require_once 'db.php';
require_once 'header.php';

// Control de Acceso
if (!isset($_SESSION['username']) || strtolower($_SESSION['username']) !== 'kevin') {
    echo "<div class='container'><div class='alert alert-danger'>Access Denied. You must be logged in as Kevin to access this page.</div></div>";
    require_once 'footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $imagePath = '';

    // Manejar Subida de Archivos
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $dest_path;
            } else {
                $error = 'Error moving the file to the upload directory.';
            }
        } else {
            $error = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } elseif (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
        // Reserva o alternativa: entrada de URL
        $imagePath = trim($_POST['image_url']);
    } else {
        $error = 'Please upload an image or provide a URL.';
    }

    if (empty($error)) {
        if (empty($name) || $price <= 0 || empty($category)) {
            $error = 'Please fill in all required fields correctly.';
        } else {
            try {
                $db = new Database();
                $stmt = $db->prepare("INSERT INTO products (name, description, price, image, category) VALUES (:name, :description, :price, :image, :category)");
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':description', $description);
                $stmt->bindValue(':price', $price);
                $stmt->bindValue(':image', $imagePath);
                $stmt->bindValue(':category', $category);
                $stmt->execute();
                $success = 'Product added successfully!';
            } catch (Exception $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<div class="container">
    <div class="auth-form-container" style="max-width: 600px;">
        <h2>Añadir nuevo producto</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="add_product.php" method="POST" enctype="multipart/form-data" class="auth-form">
            <div class="form-group">
                <label for="name">Nombre del producto</label>
                <input type="text" id="name" name="name" required
                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="price">Precio ($)</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required
                    value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="category">Categoría</label>
                <select id="category" name="category" required
                    style="width: 100%; padding: 0.8rem; border-radius: 8px; background: rgba(0,0,0,0.2); color: var(--text-color); border: 1px solid rgba(255, 143, 163, 0.2);">
                    <option value="Hot Coffee">Hot Coffee</option>
                    <option value="Cold Coffee">Cold Coffee</option>
                    <option value="Bakery">Bakery</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Imagen del producto (Subir)</label>
                <input type="file" id="image" name="image" accept="image/*" style="padding: 0.5rem;">
            </div>

            <div class="form-group">
                <label for="image_url">O URL de la imagen (opcional si subes)</label>
                <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4"
                    style="width: 100%; padding: 0.8rem; border-radius: 8px; background: rgba(0,0,0,0.2); color: var(--text-color); border: 1px solid rgba(255, 143, 163, 0.2);"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Producto</button>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>