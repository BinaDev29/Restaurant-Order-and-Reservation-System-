<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

class UserController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function index($search = '')
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (full_name LIKE ? OR email LIKE ?)";
            $term = "%$search%";
            $params = [$term, $term];
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getEmployees($search = '')
    {
        $sql = "SELECT u.*, e.position, e.salary, e.hire_date, e.status as emp_status 
                FROM users u 
                LEFT JOIN employees e ON u.id = e.user_id 
                WHERE u.role = 'staff'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR e.position LIKE ?)";
            $term = "%$search%";
            $params = [$term, $term, $term];
        }

        $sql .= " ORDER BY u.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($name, $email, $password, $phone, $role = 'staff')
    {
        // Check if email exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false; // Email exists
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hash, $phone, $role]);
    }

    public function createEmployee($name, $email, $password, $phone, $position, $salary, $hire_date)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Create User
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->pdo->rollBack();
                return false; // Email exists
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, 'staff')");
            $stmt->execute([$name, $email, $hash, $phone]);
            $userId = $this->pdo->lastInsertId();

            // 2. Create Employee Record
            $stmt = $this->pdo->prepare("INSERT INTO employees (user_id, position, salary, hire_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $position, $salary, $hire_date]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateProfile($id, $name, $phone, $password = null)
    {
        if ($password) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("UPDATE users SET full_name = ?, phone = ?, password = ? WHERE id = ?");
            return $stmt->execute([$name, $phone, $hash, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
            return $stmt->execute([$name, $phone, $id]);
        }
    }
}
