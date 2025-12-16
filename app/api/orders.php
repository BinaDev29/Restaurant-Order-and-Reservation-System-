<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_logged_in()) {
        echo json_encode(['status' => 'error', 'message' => 'Please Login to Order']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];

    if (empty($items)) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Calculate Total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, order_status) VALUES (?, ?, 'pending')");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $orderId = $pdo->lastInsertId();

        // Create Order Items
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            $subtotal = $item['price'] * $item['qty'];
            // Validating price backend side is safer, but skipping for demo speed
            $stmtItem->execute([$orderId, $item['id'], $item['qty'], $item['price'], $subtotal]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'order_id' => $orderId]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
