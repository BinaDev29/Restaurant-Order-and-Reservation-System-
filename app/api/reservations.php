<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic API handling
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // If user is guest, we might create a user or just store contact info. 
    // For this simple system, let's require login or store partial info if schema allows users to be null (it does, user_id is nullable).
    // But table schema has foreign key.

    $date = sanitize_input($_POST['date']);
    $time = sanitize_input($_POST['time']);
    $guests = (int) $_POST['guests'];
    $phone = sanitize_input($_POST['phone']);
    $status = 'pending';

    // Random table assignment or null
    $table_id = null; // To be assigned by admin

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, reservation_date, reservation_time, guests, status, special_request) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $date, $time, $guests, $status, "Phone: $phone"]);

        // Return JSON if AJAX, or Redirect
        // The form in index.php was a standard POST, so redirect.
        // If it was JS fetch, we'd return JSON.

        // Check if request expects JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['status' => 'success', 'message' => 'Reservation Request Sent!']);
        } else {
            // Redirect with flash
            // Ideally we need session for flash, config handles session_start
            $_SESSION['flash_message'] = "Reservation Requested Successfully!";
            header("Location: " . APP_URL . "/index.php?reservation=success");
        }

    } catch (Exception $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
