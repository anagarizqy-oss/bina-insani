<?php
// dashboard/admin/function_admin/add_siswa.php
include '../../../includes/auth.php';
include '../../../config/db.php';
include '../../../includes/csrf.php';
must_be(['admin']);

$error = '';
$success = '';
$data = [];
$is_edit = false;

// CHECK IF EDIT MODE
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if ($data) {
        $is_edit = true;
    } else {
        $error = "Data siswa tidak ditemukan.";
    }
}

// Fetch Kelas List for Dropdown
$kelas_list = $pdo->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $absen = (int)$_POST['absen'];
    $kelas_id = (int)$_POST['kelas_id']; // New Class ID
    $nis = clean($_POST['nis']);
    $no_hp = clean($_POST['no_hp']);

    if (empty($nama_lengkap) || empty($kelas_id) || empty($nis)) {
        $error = "Semua field bertanda * wajib diisi.";
    } else {
        try {
            $pdo->beginTransaction();

            if ($is_edit) {
                // UPDATE LOGIC
                // Update users table
                $stmt_user = $pdo->prepare("UPDATE users SET nama_lengkap = ? WHERE id = ?");
                $stmt_user->execute([$nama_lengkap, $data['user_id']]);

                // Update siswa table
                $stmt_siswa = $pdo->prepare("UPDATE siswa SET nis = ?, nama_lengkap = ?, absen = ?, kelas_id = ?, no_hp = ? WHERE id = ?");
                $stmt_siswa->execute([$nis, $nama_lengkap, $absen, $kelas_id, $no_hp, $data['id']]);

                $pdo->commit();
                header("Location: ../data_siswa.php");
                exit;
            } else {
                // INSERT LOGIC
                // GENERATE CREDENTIALS
                // 1. Username: sw_{2 huruf nama}{nomor urut}
                $two_chars = strtolower(substr(str_replace(' ', '', $nama_lengkap), 0, 2));

                // Get count of existing students to determine sequence number
                $stmt_count = $pdo->query("SELECT COUNT(*) FROM siswa");
                $count = $stmt_count->fetchColumn();
                $sequence = $count + 1;

                $username = "sw_" . $two_chars . $sequence;

                // 2. Password: (inisial 3 huruf depan)(SW)(4 karakter acak) - Simpler pattern since we don't have explicit jurusan/nomor codes easily
                $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama_lengkap), 0, 3)));
                $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);

                $password_plain = $three_chars . "SW" . $random_chars;

                // 1. Create User
                $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);
                $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'siswa', ?)");
                $stmt_user->execute([$username, $hashed_password, $nama_lengkap]);
                $user_id = $pdo->lastInsertId();

                // 2. Insert Siswa
                // Note: Old columns (kelas, jurusan, nomor_kelas) are left as NULL
                $stmt_siswa = $pdo->prepare("INSERT INTO siswa (user_id, nis, nama_lengkap, absen, kelas_id, no_hp, password_plain) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt_siswa->execute([$user_id, $nis, $nama_lengkap, $absen, $kelas_id, $no_hp, $password_plain]);

                $pdo->commit();
                header("Location: ../data_siswa.php");
                exit;
            }
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
    <link rel="stylesheet" href="../../../assets/css/admin.css">
</head>

<body>
    <div class="main-content" style="margin-left: 0; width: 100%; max-width: 600px; margin: 2rem auto;">
        <div class="card">
            <h2><?= $is_edit ? 'Edit Data Siswa' : 'Tambah Siswa Baru' ?></h2>
            <p class="subtitle"><?= $is_edit ? 'Perbarui data siswa' : 'Buat akun dan data siswa baru' ?></p>

            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>NIS *</label>
                <input type="number" name="nis" required value="<?= $is_edit ? htmlspecialchars($data['nis']) : '' ?>">

                <label>Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required value="<?= $is_edit ? htmlspecialchars($data['nama_lengkap']) : '' ?>">

                <label>Kelas *</label>
                <select name="kelas_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                    <option value="">-- Pilih Kelas --</option>
                    <?php while ($k = $kelas_list->fetch()): ?>
                        <option value="<?= $k['id'] ?>" <?= ($is_edit && isset($data['kelas_id']) && $data['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>No. Absen *</label>
                <input type="number" name="absen" min="1" required value="<?= $is_edit ? htmlspecialchars($data['absen']) : '' ?>" style="margin-top: 5px;">

                <label>Nomor HP</label>
                <input type="text" name="no_hp" value="<?= $is_edit ? htmlspecialchars($data['no_hp'] ?? '') : '' ?>">

                <?php if (!$is_edit): ?>
                    <div style="background: #e8f5e9; padding: 10px; border-radius: 6px; margin: 10px 0; font-size: 0.9em; color: #2e7d32;">
                        <strong>Info Akun Otomatis:</strong><br>
                        Username: <em>(random: sw_####)</em><br>
                        Password Default: <strong>[3HurufNama]SW[4Acak]</strong>
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="../data_siswa.php" style="flex: 1; padding: 12px; text-align: center; border: 1px solid #ddd; border-radius: 8px; color: #666; text-decoration: none;">Batal</a>
                    <button type="submit" style="flex: 2; border: none; background: #2575fc; color: white; border-radius: 8px; cursor: pointer;"><?= $is_edit ? 'Simpan Perubahan' : 'Simpan Data' ?></button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>