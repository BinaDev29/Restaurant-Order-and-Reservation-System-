<?php
// Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration Constants
define('APP_NAME', 'Golden Bar and Restaurant');
if (!defined('APP_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    define('APP_URL', $protocol . $host);
}
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'restaurant_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // If database doesn't exist, try to create it (Development only)
    try {
        $dsn_no_db = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
        $pdo_temp = new PDO($dsn_no_db, DB_USER, DB_PASS, $options);
        $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $pdo_temp = null;

        // Retry connection
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        // Import Schema
        if (file_exists(__DIR__ . '/../database/schema.sql')) {
            $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
            $pdo->exec($sql);
        }
    } catch (PDOException $e2) {
        die("Connection failed: " . $e2->getMessage());
    }
}

// Set Timezone
date_default_timezone_set('UTC'); // Or Africa/Addis_Ababa for Ethiopian context
