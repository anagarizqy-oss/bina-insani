<?php
// dashboard/admin/function_admin/import_guru.php
include '../../includes/auth.php';
include '../../config/db.php';
include '../../includes/csrf.php';

must_be(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_csv'])) {

    // Validasi file
    $csvMimes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'];

    if (!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'], $csvMimes)) {

        if (is_uploaded_file($_FILES['file_csv']['tmp_name'])) {

            $csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');

            // Skip Header Row
            fgetcsv($csvFile);

            $success_count = 0;
            $fail_count = 0;
            $errors = [];

            $pdo->beginTransaction();

            try {
                // Get current guru count for username generation
                $stmt = $pdo->query("SELECT COUNT(*) FROM guru");
                $total_guru = $stmt->fetchColumn();

                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    // EXPECTED FORMAT: [0]NUPTK, [1]Nama, [2]Mapel, [3]IsWali(0/1), [4]KelasWali

                    $nuptk = trim($line[0] ?? '');
                    $nama = trim($line[1] ?? '');
                    $mapel = trim($line[2] ?? '');
                    $is_wali = (int)($line[3] ?? 0);
                    $kelas_wali = trim($line[4] ?? '');

                    if (empty($nuptk) || empty($nama)) {
                        $fail_count++;
                        continue;
                    }

                    // Check duplicate NUPTK
                    $stmt_check = $pdo->prepare("SELECT id FROM guru WHERE nuptk = ?");
                    $stmt_check->execute([$nuptk]);
                    if ($stmt_check->rowCount() > 0) {
                        $fail_count++;
                        $errors[] = "NUPTK $nuptk sudah ada (lewat).";
                        continue;
                    }

                    // Generate Credentials
                    $total_guru++;
                    $two_chars = strtolower(substr(str_replace(' ', '', $nama), 0, 2));
                    $username = "gr_" . $two_chars . $total_guru;

                    $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama), 0, 3)));
                    $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
                    $password_plain = $three_chars . $random_chars;
                    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

                    // Insert User
                    $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'guru', ?)");
                    $stmt_user->execute([$username, $hashed_password, $nama]);
                    $user_id = $pdo->lastInsertId();

                    // Insert Guru
                    $stmt_guru = $pdo->prepare("INSERT INTO guru (user_id, nuptk, nama_lengkap, mata_pelajaran, is_wali_kelas, kelas_wali, password_plain) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt_guru->execute([$user_id, $nuptk, $nama, $mapel, $is_wali, $kelas_wali, $password_plain]);

                    $success_count++;
                }

                $pdo->commit();

                $msg = "Impor Selesai. Sukses: $success_count. Gagal/Duplikat: $fail_count.";
                if (!empty($errors)) {
                    // Optionally log errors or show first few
                }

                $_SESSION['success_message'] = $msg;
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error_message'] = "Terjadi kesalahan sistem: " . $e->getMessage();
            }

            fclose($csvFile);
        } else {
            $_SESSION['error_message'] = "Gagal membaca file.";
        }
    } else {
        $_SESSION['error_message'] = "Format file harus CSV.";
    }
} else {
    $_SESSION['error_message'] = "Tidak ada file yang diunggah.";
}

header("Location: ../data_guru.php");
exit;
