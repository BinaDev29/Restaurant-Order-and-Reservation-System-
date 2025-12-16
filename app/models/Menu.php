<?php

class Menu
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }

    public function getAllItems()
    {
        $stmt = $this->pdo->query("SELECT m.*, c.name as category_name FROM menu_items m JOIN categories c ON m.category_id = c.id WHERE m.is_available = 1");
        return $stmt->fetchAll();
    }

    public function getFeaturedItems()
    {
        // Fetch latest items added by Admin
        $stmt = $this->pdo->query("SELECT * FROM menu_items WHERE is_available = 1 ORDER BY id DESC LIMIT 6");
        return $stmt->fetchAll();
    }
}
