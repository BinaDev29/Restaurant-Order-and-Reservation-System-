<?php
require_once 'app/config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS employees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        position VARCHAR(50) NOT NULL,
        salary DECIMAL(10, 2) NOT NULL,
        hire_date DATE NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );";

    $pdo->exec($sql);
    echo "Employee table created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
