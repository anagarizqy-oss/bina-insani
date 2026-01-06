<?php
// create_table_kelas.php
include 'config/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS kelas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_kelas VARCHAR(50) NOT NULL UNIQUE
    )";
    $pdo->exec($sql);
    echo "Table 'kelas' created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
