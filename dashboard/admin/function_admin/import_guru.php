<?php
// dashboard/admin/function_admin/import_guru.php
require '../../../vendor/autoload.php'; // Load Composer Autoload

use PhpOffice\PhpSpreadsheet\IOFactory;

include '../../../includes/auth.php';
include '../../../config/db.php';
// include '../../includes/csrf.php';

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
            $rows = $sheet->toArray();

            $success_count = 0;
            $fail_count = 0;
            $pdo->beginTransaction();

            $stmt = $pdo->query("SELECT COUNT(*) FROM guru");
            $total_guru = $stmt->fetchColumn();

            $errors = [];

            // Iterate rows, skip header (index 0)
            foreach ($rows as $index => $line) {
                $row_number = $index + 1;
                if ($index === 0) continue; // Skip Header

                // Check if row is empty
                if (empty(array_filter($line))) continue;

                // STRUCTURE BASED ON EXPORT / USER IMAGE
                // [0]No, [1]UserID, [2]NUPTK, [3]Nama, [4]Mapel, [5]NoHP, [6]Password, [7]IsWali, [8]KelasWali, [9]FotoProfil

                $nuptk = trim($line[2] ?? '');
                $nama = trim($line[3] ?? '');
                $mapel = trim($line[4] ?? '');
                $no_hp = trim($line[5] ?? '');
                $password_input = trim($line[6] ?? '');
                $is_wali = (int)($line[7] ?? 0);
                $kelas_wali = trim($line[8] ?? '');

                // Validation
                if (empty($nuptk) || empty($nama)) {
                    $fail_count++;
                    $errors[] = "Baris $row_number: NUPTK atau Nama kosong.";
                    continue; // Skip invalid rows
                }

                // Check Duplicate NUPTK
                $stmt_check = $pdo->prepare("SELECT id FROM guru WHERE nuptk = ?");
                $stmt_check->execute([$nuptk]);
                if ($stmt_check->rowCount() > 0) {
                    $fail_count++;
                    $errors[] = "Baris $row_number: NUPTK '$nuptk' sudah terdaftar.";
                    continue;
                }

                // Generate Credentials
                $total_guru++;
                $two_chars = strtolower(substr(str_replace(' ', '', $nama), 0, 2));
                if (strlen($two_chars) < 2) $two_chars = str_pad($two_chars, 2, 'x'); // Fallback

                $username = "gr_" . $two_chars . $total_guru;

                if (!empty($password_input)) {
                    $password_plain = $password_input;
                } else {
                    $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama), 0, 3)));
                    if (strlen($three_chars) < 3) $three_chars = str_pad($three_chars, 3, 'x');
                    $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
                    $password_plain = $three_chars . $random_chars;
                }

                $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

                // Insert User (Role: Guru)
                $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'guru', ?)");
                $stmt_user->execute([$username, $hashed_password, $nama]);
                $user_id = $pdo->lastInsertId();

                // Insert Guru (Added no_hp)
                $stmt_guru = $pdo->prepare("INSERT INTO guru (user_id, nuptk, nama_lengkap, mata_pelajaran, no_hp, is_wali_kelas, kelas_wali, password_plain) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_guru->execute([$user_id, $nuptk, $nama, $mapel, $no_hp, $is_wali, $kelas_wali, $password_plain]);

                $success_count++;
            }

            $pdo->commit();

            $msg = "Impor Selesai. Sukses: $success_count. Gagal: $fail_count.";
            if (!empty($errors)) {
                $msg .= "<br><br>Detail Kegagalan:<br>" . implode("<br>", array_slice($errors, 0, 10)); // Limit to 10 errors
                if (count($errors) > 10) $msg .= "<br>...dan " . (count($errors) - 10) . " error lainnya.";
            }
            $_SESSION['success_message'] = $msg;
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

header("Location: ../data_guru.php");
exit;
