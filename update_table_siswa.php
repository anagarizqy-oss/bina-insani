<?php
// update_table_siswa.php
include 'config/db.php';

try {
    // 1. Add kelas_id column
    $stmt = $pdo->query("SHOW COLUMNS FROM siswa LIKE 'kelas_id'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE siswa ADD COLUMN kelas_id INT NULL AFTER absen");
        $pdo->exec("ALTER TABLE siswa ADD CONSTRAINT fk_siswa_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL");
        echo "Column 'kelas_id' added.\n";
    }

    // 2. Make old columns nullable (so we can ignore them)
    // We assume they are VARCHAR or INT based on usage
    $pdo->exec("ALTER TABLE siswa MODIFY COLUMN kelas VARCHAR(10) NULL");
    $pdo->exec("ALTER TABLE siswa MODIFY COLUMN jurusan VARCHAR(20) NULL");
    $pdo->exec("ALTER TABLE siswa MODIFY COLUMN nomor_kelas INT NULL");
    echo "Old columns modified to NULL.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
