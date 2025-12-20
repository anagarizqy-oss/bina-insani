<?php
// dashboard/admin/function_admin/export_siswa_xlsx.php

// WAJIB: cegah output liar (spasi, BOM, notice)
ob_start();

require '../../../vendor/autoload.php';
require '../../../includes/auth.php';
require '../../../config/db.php';

must_be(['admin']);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// ================= BUAT SPREADSHEET =================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Siswa');

// ================= HEADER =================
$headers = [
    'A1' => 'No',
    'B1' => 'User ID',
    'C1' => 'NIS',
    'D1' => 'Nama Lengkap',
    'E1' => 'Absen',
    'F1' => 'Kelas',
    'G1' => 'Jurusan',
    'H1' => 'Nomor Kelas',
    'I1' => 'No HP',
    'J1' => 'Password'
];

foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

// ================= STYLE HEADER =================
$sheet->getStyle('A1:J1')->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4F81BD']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

// ================= QUERY DATA =================
$stmt = $pdo->query("
    SELECT
        user_id,
        nis,
        nama_lengkap,
        absen,
        kelas,
        jurusan,
        nomor_kelas,
        no_hp,
        password_plain
    FROM siswa
    ORDER BY kelas ASC, jurusan ASC, nomor_kelas ASC, absen ASC
");

// ================= ISI DATA =================
$rowNum = 2;
$no = 1;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $sheet->setCellValue('A' . $rowNum, $no++);

    $sheet->setCellValueExplicit(
        'B' . $rowNum,
        $row['user_id'],
        DataType::TYPE_STRING
    );

    // NIS → PAKSA TEXT
    $sheet->setCellValueExplicit(
        'C' . $rowNum,
        $row['nis'],
        DataType::TYPE_STRING
    );

    $sheet->setCellValue('D' . $rowNum, $row['nama_lengkap']);
    $sheet->setCellValue('E' . $rowNum, $row['absen']);
    $sheet->setCellValue('F' . $rowNum, $row['kelas']);
    $sheet->setCellValue('G' . $rowNum, $row['jurusan']);
    $sheet->setCellValue('H' . $rowNum, $row['nomor_kelas']);

    // No HP → TEXT
    $sheet->setCellValueExplicit(
        'I' . $rowNum,
        $row['no_hp'],
        DataType::TYPE_STRING
    );

    $sheet->setCellValue('J' . $rowNum, $row['password_plain']);

    $rowNum++;
}

// ================= AUTO WIDTH =================
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ================= OUTPUT =================
$filename = 'data_siswa_' . date('Y-m-d') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
