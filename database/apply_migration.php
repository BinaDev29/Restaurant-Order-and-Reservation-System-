<?php
require_once __DIR__ . '/../app/config.php';

try {
    echo "Applying database migrations...\n";

    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/update_roles.sql');

    // Execute
    $pdo->exec($sql);

    echo "Migration successful: Roles updated to include 'waiter' and 'chef'.\n";

} catch (PDOException $e) {
    echo "Migration failed (might have already been run): " . $e->getMessage() . "\n";
}
