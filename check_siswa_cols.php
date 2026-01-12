<?php
include 'config/db.php';
try {
    $stmt = $pdo->query("DESCRIBE siswa");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) echo $c['Field'] . " - " . $c['Type'] . "\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
