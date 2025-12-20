<?php
// dashboard/admin/function_admin/import_siswa.php
include '../../includes/auth.php';
include '../../config/db.php';
include '../../includes/csrf.php';

must_be(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_csv'])) {

    $csvMimes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'];

    if (!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'], $csvMimes)) {

        if (is_uploaded_file($_FILES['file_csv']['tmp_name'])) {

            $csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');

            // Skip Header
            fgetcsv($csvFile);

            $success_count = 0;
            $fail_count = 0;

            $pdo->beginTransaction();

            try {
                $stmt = $pdo->query("SELECT COUNT(*) FROM siswa");
                $total_siswa = $stmt->fetchColumn();

                while (($line = fgetcsv($csvFile)) !== FALSE) {
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
                        $fail_count++;
                        continue;
                    }

                    // Generate Credentials
                    $total_siswa++;
                    $two_chars = strtolower(substr(str_replace(' ', '', $nama), 0, 2));
                    $username = "sw_" . $two_chars . $total_siswa;

                    $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama), 0, 3)));
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
                $_SESSION['success_message'] = "Impor Selesai. Sukses: $success_count. Gagal/Duplikat: $fail_count.";
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

header("Location: ../data_siswa.php");
exit;
