<?php
// update_table_kelas.php
include 'config/db.php';

try {
    // Check if column exists first
    $stmt = $pdo->query("SHOW COLUMNS FROM kelas LIKE 'wali_kelas_id'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $sql = "ALTER TABLE kelas ADD COLUMN wali_kelas_id INT NULL,
                ADD CONSTRAINT fk_wali_kelas FOREIGN KEY (wali_kelas_id) REFERENCES guru(id) ON DELETE SET NULL";
        $pdo->exec($sql);
        echo "Column 'wali_kelas_id' added successfully.";
    } else {
        echo "Column 'wali_kelas_id' already exists.";
    }
} catch (PDOException $e) {
    echo "Error updating table: " . $e->getMessage();
}
