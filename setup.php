<?php
require_once 'db.php';

$db = new Database();

try {
    // Create Products Table
    $productsTable = "CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        price REAL NOT NULL,
        image TEXT,
        category TEXT
    )";
    $db->exec($productsTable);

    // Create Orders Table
    $ordersTable = "CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        customer_name TEXT,
        total_amount REAL,
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        items TEXT
    )";
    $db->exec($ordersTable);

    // Create Users Table
    $usersTable = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $db->exec($usersTable);

    // Seed Products
    $result = $db->query("SELECT COUNT(*) as count FROM products");
    $row = $result->fetch();

    if ($row['count'] == 0) {
        $products = [
            ['Espresso', 'Rich and bold single shot of espresso.', 2.50, 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=500&auto=format&fit=crop&q=60', 'Hot Coffee'],
            ['Cappuccino', 'Espresso with steamed milk and foam.', 3.50, 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&auto=format&fit=crop&q=60', 'Hot Coffee'],
            ['Latte', 'Espresso with a lot of steamed milk.', 3.75, 'https://images.unsplash.com/photo-1593443320739-77f74952dabd?w=500&auto=format&fit=crop&q=60', 'Hot Coffee'],
            ['Iced Americano', 'Espresso shots topped with water and ice.', 3.00, 'https://images.unsplash.com/photo-1556484687-306361646d8e?w=500&auto=format&fit=crop&q=60', 'Cold Coffee'],
            ['Croissant', 'Buttery and flaky french pastry.', 2.00, 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500&auto=format&fit=crop&q=60', 'Bakery'],
            ['Blueberry Muffin', 'Freshly baked muffin with blueberries.', 2.25, 'https://images.unsplash.com/photo-1607958996333-41aef7caefaa?w=500&auto=format&fit=crop&q=60', 'Bakery']
        ];

        $stmt = $db->prepare("INSERT INTO products (name, description, price, image, category) VALUES (:name, :description, :price, :image, :category)");

        foreach ($products as $product) {
            $stmt->bindValue(':name', $product[0]);
            $stmt->bindValue(':description', $product[1]);
            $stmt->bindValue(':price', $product[2]);
            $stmt->bindValue(':image', $product[3]);
            $stmt->bindValue(':category', $product[4]);
            $stmt->execute();
        }
        echo "Database initialized and seeded with products.<br>";
    } else {
        echo "Database already initialized.<br>";
    }

    echo "Setup Complete.";

} catch (PDOException $e) {
    echo "Setup Error: " . $e->getMessage();
}
?>