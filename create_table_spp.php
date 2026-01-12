<?php
include 'config/db.php';

try {
    $pdo->exec("DROP TABLE IF EXISTS spp");

    $query = "CREATE TABLE spp (
        id INT AUTO_INCREMENT PRIMARY KEY,
        siswa_id INT NOT NULL,
        bulan VARCHAR(20) NOT NULL,
        tahun VARCHAR(10) NOT NULL,
        jumlah DECIMAL(10,2) NOT NULL DEFAULT 0,
        status ENUM('Unpaid', 'Paid', 'Expired') DEFAULT 'Unpaid',
        xendit_invoice_id VARCHAR(100) DEFAULT NULL,
        xendit_payment_url VARCHAR(255) DEFAULT NULL,
        tanggal_bayar DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
    )";
    $pdo->exec($query);
    echo "Table 'spp' created successfully.\n";

    // Insert Dummy Data for testing (optional, but helpful)
    // Get first student
    $stmt = $pdo->query("SELECT id FROM siswa LIMIT 1");
    $siswa_id = $stmt->fetchColumn();

    if ($siswa_id) {
        $stmt_ins = $pdo->prepare("INSERT INTO spp (siswa_id, bulan, tahun, jumlah) VALUES (?, ?, ?, ?)");
        $months = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; // Semester 1
        foreach ($months as $m) {
            $stmt_ins->execute([$siswa_id, $m, '2025', 150000]); // SPP 150k
        }
        echo "Dummy SPP data inserted for Student ID $siswa_id.\n";
    }
} catch (PDOException $e) {
    die("Error creating table: " . $e->getMessage());
}
