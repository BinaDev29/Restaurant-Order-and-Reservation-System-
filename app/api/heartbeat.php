<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user = current_user();
$response = [];

// pending orders (For Admin/Chef/Waiter)
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'");
$response['pending_orders'] = $stmt->fetchColumn();

// active orders (preparing/ready - For Chef/Waiter)
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status IN ('confirmed', 'preparing')");
$response['active_orders'] = $stmt->fetchColumn();

// ready orders (For Waiter to serve)
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'ready'");
$response['ready_orders'] = $stmt->fetchColumn();

// reservations today
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE reservation_date = CURRENT_DATE AND status = 'confirmed'");
$stmt->execute();
$response['reservations_today'] = $stmt->fetchColumn();

echo json_encode($response);
