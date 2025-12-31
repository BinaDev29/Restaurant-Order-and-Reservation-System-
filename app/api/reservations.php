<?php
ob_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic API handling
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Sanitize and capture all form fields
    $raw_date = $_POST['date'];
    $raw_time = $_POST['time'];

    // Try to normalize date format for MySQL (YYYY-MM-DD)
    $date = date('Y-m-d', strtotime($raw_date));
    // Try to normalize time format for MySQL (HH:MM:SS)
    $time = date('H:i:s', strtotime($raw_time));

    $guests = (int) $_POST['guests'];
    $phone = sanitize_input($_POST['phone']);
    $type = isset($_POST['type']) ? sanitize_input($_POST['type']) : 'Standard';
    $requests = isset($_POST['requests']) ? sanitize_input($_POST['requests']) : '';
    $status = 'pending';

    // Combine type and requests for the special_request field since schema is limited
    $full_request = "Type: " . ucfirst($type) . " | Phone: " . $phone;
    if (!empty($requests)) {
        $full_request .= " | Requests: " . $requests;
    }

    try {
        // Validate inputs
        if (!$date || $date === '1970-01-01') {
            throw new Exception("Invalid date format provided.");
        }
        if (!$time) {
            throw new Exception("Invalid time format provided.");
        }
        if (empty($phone)) {
            throw new Exception("Please provide a contact phone number.");
        }

        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, reservation_date, reservation_time, guests, status, special_request) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $date, $time, $guests, $status, $full_request]);

        // Success handling
        $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if ($is_ajax) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Reservation Request Sent!']);
            exit;
        } else {
            // Use the flash function from functions.php
            flash('reservation_success', 'Your reservation request has been sent successfully!', 'success');
            header("Location: " . APP_URL . "/index.php?reservation=success");
            exit;
        }

    } catch (Exception $e) {
        $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if ($is_ajax) {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(400); // 400 for validation/logical errors
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        } else {
            flash('reservation_error', 'Error: ' . $e->getMessage(), 'danger');
            header("Location: " . APP_URL . "/index.php#reservation");
            exit;
        }
    }
}
