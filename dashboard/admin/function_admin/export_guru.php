<?php
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
$sheet->setTitle('Data Guru');

// ================= HEADER =================
$headers = [
    'A1' => 'No',
    'B1' => 'User ID',
    'C1' => 'NUPTK',
    'D1' => 'Nama Lengkap',
    'E1' => 'Mata Pelajaran',
    'F1' => 'No HP',
    'G1' => 'Password',
    'H1' => 'Wali Kelas (1=Ya, 0=Tidak)',
    'I1' => 'Kelas Wali',
    'J1' => 'Foto Profil'
];

foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

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

// ================= DATA =================
$stmt = $pdo->query("
    SELECT 
        user_id,
        nuptk,
        nama_lengkap,
        mata_pelajaran,
        no_hp,
        password_plain,
        is_wali_kelas,
        kelas_wali,
        foto_profil
    FROM guru
    ORDER BY nama_lengkap ASC
");

$rowNum = 2;
$no = 1;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $sheet->setCellValue('A' . $rowNum, $no++);

    $sheet->setCellValueExplicit(
        'B' . $rowNum,
        $row['user_id'],
        DataType::TYPE_STRING
    );

    // NUPTK HARUS STRING (ANTI SCIENTIFIC NOTATION)
    $sheet->setCellValueExplicit(
        'C' . $rowNum,
        $row['nuptk'],
        DataType::TYPE_STRING
    );

    $sheet->setCellValue('D' . $rowNum, $row['nama_lengkap']);
    $sheet->setCellValue('E' . $rowNum, $row['mata_pelajaran']);

    $sheet->setCellValueExplicit(
        'F' . $rowNum,
        $row['no_hp'],
        DataType::TYPE_STRING
    );

    $sheet->setCellValue('G' . $rowNum, $row['password_plain']);
    $sheet->setCellValue('H' . $rowNum, $row['is_wali_kelas']);
    $sheet->setCellValue('I' . $rowNum, $row['kelas_wali']);
    $sheet->setCellValue('J' . $rowNum, $row['foto_profil']);

    $rowNum++;
}

foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
// ================= OUTPUT =================
$filename = 'data_guru_' . date('Y-m-d') . '.xlsx';

ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
