<?php
require_once __DIR__ . '/../app/config.php';

try {
    echo "Seeding Chef and Waiter users...\n";

    $password = password_hash('password123', PASSWORD_DEFAULT);

    // Create Chef
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'chef@example.com'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES ('Head Chef', 'chef@example.com', ?, 'chef')")
            ->execute([$password]);
        echo "Created user: chef@example.com / password123\n";
    } else {
        echo "User chef@example.com already exists.\n";
    }

    // Create Waiter
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'waiter@example.com'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES ('John Waiter', 'waiter@example.com', ?, 'waiter')")
            ->execute([$password]);
        echo "Created user: waiter@example.com / password123\n";
    } else {
        echo "User waiter@example.com already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error seeding users: " . $e->getMessage() . "\n";
}
