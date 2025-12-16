<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

class DashboardController
{
    private $pdo;
    private $user;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        require_login();
        $this->user = current_user();
    }

    public function getStats()
    {
        $stats = [];
        $userId = $this->user['id'];

        if ($this->user['role'] === 'admin') {
            // Admin Stats: Total Revenue (Paid), Pending Orders, Today's Reservations, Active Menu Items
            $stmt = $this->pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'paid'");
            $stats['revenue'] = $stmt->fetchColumn() ?: 0;

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'");
            $stats['pending_orders'] = $stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM reservations WHERE reservation_date = CURRENT_DATE");
            $stats['reservations_today'] = $stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM menu_items WHERE is_available = 1");
            $stats['active_items'] = $stmt->fetchColumn();

        } elseif ($this->user['role'] === 'staff') {
            // Staff Stats: Pending Orders, Today's Reservations, Occupied Tables
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders WHERE order_status IN ('pending', 'preparing', 'ready')");
            $stats['active_orders_count'] = $stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM reservations WHERE reservation_date = CURRENT_DATE");
            $stats['reservations_today'] = $stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM dining_tables WHERE status = 'occupied'");
            $stats['occupied_tables'] = $stmt->fetchColumn();

        } else {
            // User Stats: My Active Orders, My Upcoming Reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status NOT IN ('completed', 'cancelled')");
            $stmt->execute([$userId]);
            $stats['active_orders'] = $stmt->fetchColumn();

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND reservation_date >= CURRENT_DATE AND status = 'confirmed'");
            $stmt->execute([$userId]);
            $stats['upcoming_reservations'] = $stmt->fetchColumn();

            $stmt = $this->pdo->prepare("SELECT SUM(total_amount) FROM orders WHERE user_id = ? AND payment_status = 'paid'");
            $stats['total_spent'] = $stmt->fetchColumn() ?: 0;
        }

        return $stats;
    }

    public function getRecentActivity()
    {
        $userId = $this->user['id'];
        if ($this->user['role'] === 'admin' || $this->user['role'] === 'staff') {
            // Recent Orders system wide
            $stmt = $this->pdo->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY created_at DESC LIMIT 5");
        } else {
            // My Recent Orders
            $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$userId]);
        }
        return $stmt->fetchAll();
    }
}
