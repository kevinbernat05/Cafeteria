<?php
require_once 'db.php';
session_start();

// Control de Acceso
if (!isset($_SESSION['username']) || strtolower($_SESSION['username']) !== 'kevin') {
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    if ($id > 0) {
        try {
            $db = new Database();
            // Opcional: Obtener ruta de imagen para borrar archivo si quisiéramos ser exhaustivos, pero por ahora solo borrado en BD
            $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            // Redirigir al menú con parámetro de éxito (podría usarse para toast si implementáramos lectura de params GET para toasts)
            header("Location: menu.php?deleted=true");
            exit;
        } catch (Exception $e) {
            die("Error deleting product: " . $e->getMessage());
        }
    }
}

header("Location: menu.php");
exit;
?>