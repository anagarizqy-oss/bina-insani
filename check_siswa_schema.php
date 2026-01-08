<?php
include 'config/db.php';
$stmt = $pdo->query("DESCRIBE siswa");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $col) {
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}
