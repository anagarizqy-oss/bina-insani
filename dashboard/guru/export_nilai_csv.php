<?php
// dashboard/guru/export_nilai_csv.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

// Ambil semua data nilai
$stmt = $pdo->prepare("
    SELECT u.nama_lengkap AS siswa, n.mapel, n.nilai, n.semester
    FROM nilai n
    JOIN siswa s ON n.siswa_id = s.id
    JOIN users u ON s.user_id = u.id
    ORDER BY u.nama_lengkap, n.semester, n.mapel
");
$stmt->execute();
$data = $stmt->fetchAll();

// Set header untuk download CSV
$filename = 'data_nilai_sma_bina_insani_' . date('Y-m-d') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Buka output stream
$output = fopen('php://output', 'w');

// Tulis header kolom
fputcsv($output, ['No', 'Nama Siswa', 'Mata Pelajaran', 'Nilai', 'Semester']);

// Tulis data baris per baris
$rowNumber = 1;
foreach ($data as $d) {
    fputcsv($output, [
        $rowNumber++,
        $d['siswa'],
        $d['mapel'],
        number_format($d['nilai'], 1), // Format nilai jadi 1 desimal
        $d['semester']
    ]);
}

// Tutup file
fclose($output);
exit;
?>