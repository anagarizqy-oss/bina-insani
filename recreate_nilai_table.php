<?php
include 'config/db.php';

try {
    // 1. Drop existing table
    $pdo->exec("DROP TABLE IF EXISTS nilai");
    echo "Dropped existing 'nilai' table.\n";

    // 2. Create new table
    $query = "CREATE TABLE nilai (
        id INT AUTO_INCREMENT PRIMARY KEY,
        siswa_id INT NOT NULL,
        mapel VARCHAR(100) NOT NULL,
        uh1 FLOAT DEFAULT NULL,
        uh2 FLOAT DEFAULT NULL,
        uh3 FLOAT DEFAULT NULL,
        uh4 FLOAT DEFAULT NULL,
        tugas1 FLOAT DEFAULT NULL,
        tugas2 FLOAT DEFAULT NULL,
        tugas3 FLOAT DEFAULT NULL,
        tugas4 FLOAT DEFAULT NULL,
        uts FLOAT DEFAULT NULL,
        uas FLOAT DEFAULT NULL,
        semester VARCHAR(10) DEFAULT 'Ganjil',
        tahun_ajar VARCHAR(20) DEFAULT '2025/2026',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
    )";
    $pdo->exec($query);
    echo "Created new 'nilai' table with correct schema.\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
