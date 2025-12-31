<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle cancel via POST action
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['action']) && $data['action'] === 'cancel') {
        if (!is_admin()) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }
        $orderId = $data['order_id'];
        require_once __DIR__ . '/../controllers/OrderController.php';
        $controller = new OrderController();
        if ($controller->cancelOrder($orderId)) {
            echo json_encode(['status' => 'success', 'message' => 'Order cancelled']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to cancel order']);
        }
        exit;
    }

    // Existing Order Placement logic...
    if (!is_logged_in()) {
        echo json_encode(['status' => 'error', 'message' => 'Please Login to Order']);
        exit;
    }

    $items = $data['items'] ?? [];
    if (empty($items)) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        exit;
    }

    try {
        $pdo->beginTransaction();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, order_status) VALUES (?, ?, 'pending')");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $orderId = $pdo->lastInsertId();

        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $subtotal = $item['price'] * $item['qty'];
            $stmtItem->execute([$orderId, $item['id'], $item['qty'], $item['price'], $subtotal]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'order_id' => $orderId]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!is_logged_in()) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    if (isset($_GET['id'])) {
        require_once __DIR__ . '/../controllers/OrderController.php';
        $controller = new OrderController();
        $order = $controller->show($_GET['id']);
        if ($order) {
            $order['total_amount_fmt'] = format_price($order['total_amount']);
            foreach ($order['items'] as &$item) {
                $item['unit_price_fmt'] = format_price($item['unit_price']);
                $item['subtotal_fmt'] = format_price($item['subtotal']);
                $item['image_url'] = get_image_url($item['image']);
            }
            echo json_encode(['status' => 'success', 'order' => $order]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Order not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    }
}
