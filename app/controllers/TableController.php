<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

class TableController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT * FROM dining_tables ORDER BY table_number ASC");
        return $stmt->fetchAll();
    }

    public function store($tableNumber, $capacity)
    {
        $stmt = $this->pdo->prepare("INSERT INTO dining_tables (table_number, capacity) VALUES (?, ?)");
        return $stmt->execute([$tableNumber, $capacity]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM dining_tables WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id)
    {
        // Get current status
        $stmt = $this->pdo->prepare("SELECT status FROM dining_tables WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetchColumn();

        $newStatus = ($current === 'available') ? 'occupied' : 'available';

        $update = $this->pdo->prepare("UPDATE dining_tables SET status = ? WHERE id = ?");
        return $update->execute([$newStatus, $id]);
    }
}
