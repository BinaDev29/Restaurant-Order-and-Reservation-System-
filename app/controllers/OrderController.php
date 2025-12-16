<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

class OrderController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        require_login();
    }

    public function index()
    {
        $user = current_user();
        if ($user['role'] === 'admin') {
            $sql = "SELECT o.*, u.full_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
            $stmt = $this->pdo->query($sql);
        } else {
            $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user['id']]);
        }
        return $stmt->fetchAll();
    }

    public function show($id)
    {
        // Fetch order details and items
        $sql = "SELECT o.*, u.full_name, u.email, u.phone FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $order = $stmt->fetch();

        if (!$order)
            return null;

        // Check permission
        $user = current_user();
        if ($user['role'] !== 'admin' && $order['user_id'] !== $user['id']) {
            return null; // or redirect
        }

        $sqlItems = "SELECT oi.*, m.name FROM order_items oi JOIN menu_items m ON oi.menu_item_id = m.id WHERE oi.order_id = ?";
        $stmtItems = $this->pdo->prepare($sqlItems);
        $stmtItems->execute([$id]);
        $order['items'] = $stmtItems->fetchAll();

        return $order;
    }

    public function updateStatus($id, $status)
    {
        $user = current_user();
        if ($user['role'] !== 'admin')
            return false;

        $validStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses))
            return false;

        $stmt = $this->pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
