<?php
// dashboard/admin/function_admin/add_siswa.php
include '../../../includes/auth.php';
include '../../../config/db.php';
include '../../../includes/csrf.php';
must_be(['admin']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $absen = (int)$_POST['absen'];
    $kelas = clean($_POST['kelas']); // X, XI, XII
    $nis = clean($_POST['nis']);
    $jurusan = clean($_POST['jurusan']); // IPA, IPS
    $nomor_kelas = (int)$_POST['nomor_kelas']; // e.g., 1, 2, 3
    $no_hp = clean($_POST['no_hp']);

    // GENERATE CREDENTIALS
    // 1. Username: sw_{2 huruf nama}{nomor urut}
    $two_chars = strtolower(substr(str_replace(' ', '', $nama_lengkap), 0, 2));

    // Get count of existing students to determine sequence number
    $stmt_count = $pdo->query("SELECT COUNT(*) FROM siswa");
    $count = $stmt_count->fetchColumn();
    $sequence = $count + 1;

    $username = "sw_" . $two_chars . $sequence;

    // 2. Password: (inisial 3 huruf depan)(PA/PS)(4 karakter acak)
    $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama_lengkap), 0, 3)));
    $jurusan_code = ($jurusan === 'IPA') ? 'PA' : 'PS';
    $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);



    $password_plain = $three_chars . $jurusan_code . $random_chars;

    if (empty($nama_lengkap) || empty($kelas) || empty($jurusan) || empty($nis)) {
        $error = "Semua field bertanda * wajib diisi.";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. Create User
            $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);
            $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'siswa', ?)");
            $stmt_user->execute([$username, $hashed_password, $nama_lengkap]);
            $user_id = $pdo->lastInsertId();

            // 2. Insert Siswa
            $stmt_siswa = $pdo->prepare("INSERT INTO siswa (user_id, nis, nama_lengkap, absen, kelas, jurusan, nomor_kelas, no_hp, password_plain) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_siswa->execute([$user_id, $nis, $nama_lengkap, $absen, $kelas, $jurusan, $nomor_kelas, $no_hp, $password_plain]);

            $pdo->commit();
            header("Location: ../data_siswa.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Terjadi kesalahan database: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - Admin</title>
    <link rel="stylesheet" href="../../../assets/admin.css">
</head>

<body>
    <div class="main-content" style="margin-left: 0; width: 100%; max-width: 600px; margin: 2rem auto;">
        <div class="card">
            <h2>Tambah Siswa Baru</h2>
            <p class="subtitle">Buat akun dan data siswa baru</p>

            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>NIS *</label>
                <input type="number" name="nis" required>

                <label>Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label>Kelas *</label>
                        <select name="kelas" required>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div>
                        <label>Jurusan *</label>
                        <select name="jurusan" required>
                            <option value="IPA">IPA</option>
                            <option value="IPS">IPS</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label>Nomor Kelas * (contoh: 1 untuk X IPA 1)</label>
                        <input type="number" name="nomor_kelas" min="1" required>
                    </div>
                    <div>
                        <label>No. Absen *</label>
                        <input type="number" name="absen" min="1" required>
                    </div>
                </div>

                <label>Nomor HP</label>
                <input type="text" name="no_hp">

                <div style="background: #e8f5e9; padding: 10px; border-radius: 6px; margin: 10px 0; font-size: 0.9em; color: #2e7d32;">
                    <strong>Info Akun Otomatis:</strong><br>
                    Username: <em>(random: siswa####)</em><br>
                    Password Default: <strong>123456</strong>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="../data_siswa.php" style="flex: 1; padding: 12px; text-align: center; border: 1px solid #ddd; border-radius: 8px; color: #666;">Batal</a>
                    <button type="submit" style="flex: 2;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>