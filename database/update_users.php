<?php
require_once __DIR__ . '/../app/config.php';

try {
    echo "Updating user roles...\n";

    // Update Chef
    $stmt = $pdo->prepare("UPDATE users SET role = 'chef' WHERE email = 'chef@example.com'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "Successfully updated chef@example.com to 'chef'.\n";
    } else {
        echo "No user found with email 'chef@example.com' (or role was already 'chef').\n";
    }

    // Update Waiter
    $stmt = $pdo->prepare("UPDATE users SET role = 'waiter' WHERE email = 'waiter@example.com'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "Successfully updated waiter@example.com to 'waiter'.\n";
    } else {
        echo "No user found with email 'waiter@example.com' (or role was already 'waiter').\n";
    }

} catch (PDOException $e) {
    echo "Error updating users: " . $e->getMessage() . "\n";
}
