<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

class MenuController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        require_admin();
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT m.*, c.name as category_name FROM menu_items m JOIN categories c ON m.category_id = c.id ORDER BY m.created_at DESC");
        return $stmt->fetchAll();
    }

    public function getCategories()
    {
        return $this->pdo->query("SELECT * FROM categories")->fetchAll();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize_input($_POST['name']);
            $desc = sanitize_input($_POST['description']);
            $price = (float) $_POST['price'];
            $cat_id = (int) $_POST['category_id'];

            // Handle Image Upload
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Ensure upload directory exists
                $uploadDir = __DIR__ . '/../../uploads/';
                if (!file_exists($uploadDir))
                    mkdir($uploadDir, 0777, true);

                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    $image_path = $filename;
                }
            } else {
                // Use placeholder URL if provided or default
                $image_path = !empty($_POST['image_url']) ? $_POST['image_url'] : null;
            }

            $stmt = $this->pdo->prepare("INSERT INTO menu_items (category_id, name, description, price, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$cat_id, $name, $desc, $price, $image_path]);

            flash('menu_msg', 'Item added successfully!');
            redirect('dashboard/menu.php');
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->execute([$id]);
        flash('menu_msg', 'Item deleted.', 'warning');
        redirect('dashboard/menu.php');
    }
}
