<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

class ReservationController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function index($search = '')
    {
        $user = current_user();
        if ($user['role'] === 'admin') {
            // Admin sees all, ordered by date desc
            $sql = "
                SELECT r.*, u.full_name, u.email, u.phone, t.table_number 
                FROM reservations r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN dining_tables t ON r.table_id = t.id
                WHERE 1=1
            ";

            $params = [];
            if (!empty($search)) {
                $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR t.table_number LIKE ?)";
                $term = "%$search%";
                $params = [$term, $term, $term];
            }

            $sql .= " ORDER BY r.reservation_date DESC, r.reservation_time DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } else {
            // User sees their own
            $stmt = $this->pdo->prepare("
                SELECT r.*, t.table_number 
                FROM reservations r
                LEFT JOIN dining_tables t ON r.table_id = t.id
                WHERE r.user_id = ?
                ORDER BY r.reservation_date DESC
            ");
            $stmt->execute([$user['id']]);
            return $stmt->fetchAll();
        }
    }

    public function updateStatus($id, $status)
    {
        // Simple validation
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
