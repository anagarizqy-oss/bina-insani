<?php
include 'config/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS masukan_saran (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        subjek VARCHAR(150),
        pesan TEXT NOT NULL,
        tanggal_submit DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table masukan_saran created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
