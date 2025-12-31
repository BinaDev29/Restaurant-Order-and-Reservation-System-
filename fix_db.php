<?php
require_once 'app/config.php';

echo "<h2>Database Configuration Check</h2>";

try {
    // 1. Check if we can connect
    echo "Attempting to connect to database: " . DB_NAME . "...<br>";

    // 2. Check if tables exist
    $tables = ['users', 'categories', 'menu_items', 'dining_tables', 'reservations', 'orders'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "Table '$table' exists.<br>";
        } else {
            echo "Table '$table' MISSING! Attempting to fix...<br>";
            // Try to re-import schema
            if (file_exists('database/schema.sql')) {
                $sql = file_get_contents('database/schema.sql');
                // Use exec on the whole file - might fail if multiple queries aren't allowed
                // Better to split by semicolon for safety
                $queries = explode(';', $sql);
                foreach ($queries as $query) {
                    $query = trim($query);
                    if (!empty($query)) {
                        try {
                            $pdo->exec($query);
                        } catch (PDOException $qe) {
                            echo "Error executing query: " . substr($query, 0, 50) . "... -> " . $qe->getMessage() . "<br>";
                        }
                    }
                }
                echo "Re-import attempt finished.<br>";
            }
        }
    }

    echo "<br><b>Check complete. Try your reservation again now.</b>";

} catch (PDOException $e) {
    echo "Critical Error: " . $e->getMessage();
}
