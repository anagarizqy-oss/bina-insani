<?php
include 'config/db.php';

try {
    $stmt = $pdo->query("DESCRIBE nilai");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Table 'nilai' exists.\n";
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
} catch (PDOException $e) {
    echo "Table 'nilai' does not exist or error: " . $e->getMessage();
}
