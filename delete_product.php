<?php
require_once 'db.php';
session_start();

// Access Control
if (!isset($_SESSION['username']) || strtolower($_SESSION['username']) !== 'kevin') {
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        try {
            $db = new Database();
            // Optional: Get image path to delete file if we wanted to be thorough, but for now just DB delete
            $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            // Redirect back to menu with success param (could be used for toast if we implemented reading GET params for toasts)
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
