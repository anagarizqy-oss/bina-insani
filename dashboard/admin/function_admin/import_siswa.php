<?php
// dashboard/admin/function_admin/import_siswa.php
require '../../../vendor/autoload.php'; // Load Composer Autoload

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

include '../../includes/auth.php';
include '../../config/db.php';
// include '../../includes/csrf.php'; // CSRF removed for file upload simplicity in legacy auth, add back if needed

must_be(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_excel'])) {

    $allowedExtensions = ['csv', 'xls', 'xlsx'];
    $fileName = $_FILES['file_excel']['name'];
    $fileTmp = $_FILES['file_excel']['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedExtensions)) {

        try {
            $spreadsheet = IOFactory::load($fileTmp);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(); // Get all data as array

            $success_count = 0;
            $fail_count = 0;
            $pdo->beginTransaction();

            $stmt = $pdo->query("SELECT COUNT(*) FROM siswa");
            $total_siswa = $stmt->fetchColumn();

            // Iterate rows, skip header (index 0)
            foreach ($rows as $index => $line) {
                if ($index === 0) continue; // Skip Header

                // Check if row is empty
                if (empty(array_filter($line))) continue;

                // FORMAT: [0]NIS, [1]Nama, [2]Kelas, [3]Jurusan, [4]NoKelas, [5]Absen, [6]NoHP
                $nis = trim($line[0] ?? '');
                $nama = trim($line[1] ?? '');
                $kelas = trim($line[2] ?? '');
                $jurusan = trim($line[3] ?? '');
                $no_kelas = (int)($line[4] ?? 1);
                $absen = (int)($line[5] ?? 0);
                $no_hp = trim($line[6] ?? '');

                if (empty($nis) || empty($nama)) {
                    $fail_count++;
                    continue;
                }

                // Check Duplicate NIS
                $stmt_check = $pdo->prepare("SELECT id FROM siswa WHERE nis = ?");
                $stmt_check->execute([$nis]);
                if ($stmt_check->rowCount() > 0) {
                    $fail_count++; // Mark duplicate as fail or skip
                    continue;
                }

                // Generate Credentials
                $total_siswa++;
                $two_chars = strtolower(substr(str_replace(' ', '', $nama), 0, 2));
                // Handle short names
                if (strlen($two_chars) < 2) $two_chars = str_pad($two_chars, 2, 'x');

                $username = "sw_" . $two_chars . $total_siswa;

                $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama), 0, 3)));
                if (strlen($three_chars) < 3) $three_chars = str_pad($three_chars, 3, 'x');

                $jurusan_code = ($jurusan === 'IPA') ? 'PA' : 'PS';
                $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);

                $password_plain = $three_chars . $jurusan_code . $random_chars;
                $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

                // Insert User
                $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'siswa', ?)");
                $stmt_user->execute([$username, $hashed_password, $nama]);
                $user_id = $pdo->lastInsertId();

                // Insert Siswa
                $stmt_siswa = $pdo->prepare("INSERT INTO siswa (user_id, nis, nama_lengkap, absen, kelas, jurusan, nomor_kelas, no_hp, password_plain) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_siswa->execute([$user_id, $nis, $nama, $absen, $kelas, $jurusan, $no_kelas, $no_hp, $password_plain]);

                $success_count++;
            }

            $pdo->commit();
            $_SESSION['success_message'] = "Impor Selesai. Sukses: $success_count. Gagal/Duplikat/Kosong: $fail_count.";
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['error_message'] = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv";
    }
} else {
    $_SESSION['error_message'] = "Tidak ada file yang diunggah.";
}

header("Location: ../data_siswa.php");
exit;
