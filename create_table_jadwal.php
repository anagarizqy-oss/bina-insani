<?php
// create_table_jadwal.php
include 'config/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS jadwal_pelajaran (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kelas_id INT NOT NULL,
        guru_id INT NOT NULL,
        hari VARCHAR(20) NOT NULL,
        jam_mulai TIME NOT NULL,
        jam_selesai TIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
        FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "Table 'jadwal_pelajaran' created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
