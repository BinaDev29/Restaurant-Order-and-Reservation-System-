<?php
require 'app/config.php';

try {
    // Disable foreign key checks to allow truncation
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE menu_items");
    $pdo->exec("TRUNCATE TABLE categories");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "Static data cleared successfully. System is now empty ready for Admin input.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
