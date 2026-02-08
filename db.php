<?php
class Database
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('sqlite:coffee_shop.db');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}
?>