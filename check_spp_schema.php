<?php
include 'config/db.php';
try {
    $stmt = $pdo->query("DESCRIBE spp");
    echo "Table 'spp' exists.\n";
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) echo $c['Field'] . " - " . $c['Type'] . "\n";
} catch (PDOException $e) {
    echo "Table 'spp' not found.\n";
}
