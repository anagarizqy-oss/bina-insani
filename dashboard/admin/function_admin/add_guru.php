<?php
// dashboard/admin/function_admin/add_guru.php
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
    $stmt = $pdo->prepare("SELECT * FROM guru WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if ($data) {
        $is_edit = true;
    } else {
        $error = "Data guru tidak ditemukan.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $is_wali_kelas = isset($_POST['is_wali_kelas']) ? 1 : 0;

    $kelas_wali_input = clean($_POST['kelas_wali']); // e.g., "X IPA 1"
    $kelas_wali = ($is_wali_kelas && !empty($kelas_wali_input)) ? $kelas_wali_input : null;
    $nuptk = clean($_POST['nuptk']);
    $mata_pelajaran = clean($_POST['mata_pelajaran']);

    if (empty($nama_lengkap) || empty($nuptk) || empty($mata_pelajaran)) {
        $error = "Semua field bertanda * wajib diisi.";
    } else {
        try {
            $pdo->beginTransaction();

            if ($is_edit) {
                // UPDATE LOGIC
                // Update users table for name sync
                $stmt_user = $pdo->prepare("UPDATE users SET nama_lengkap = ? WHERE id = ?");
                $stmt_user->execute([$nama_lengkap, $data['user_id']]);

                // Update guru table
                $stmt_guru = $pdo->prepare("UPDATE guru SET nuptk = ?, nama_lengkap = ?, mata_pelajaran = ?, is_wali_kelas = ?, kelas_wali = ? WHERE id = ?");
                $stmt_guru->execute([$nuptk, $nama_lengkap, $mata_pelajaran, $is_wali_kelas, $kelas_wali, $data['id']]);

                // Redirect logic
                $pdo->commit();
                header("Location: ../data_guru.php");
                exit;
            } else {
                // INSERT LOGIC (Original)
                // 1. Username: gr_{2 huruf nama}{nomor urut}
                $two_chars = strtolower(substr(str_replace(' ', '', $nama_lengkap), 0, 2));

                // Get count for sequence
                $stmt_count = $pdo->query("SELECT COUNT(*) FROM guru");
                $count = $stmt_count->fetchColumn();
                $sequence = $count + 1;

                $username = "gr_" . $two_chars . $sequence;

                // 2. Password: (3 huruf depan nama lengkap)(3 karakter acak)
                $three_chars = ucfirst(strtolower(substr(str_replace(' ', '', $nama_lengkap), 0, 3)));
                $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);

                $password_plain = $three_chars . $random_chars;

                // 1. Create User
                $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);
                $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'guru', ?)");
                $stmt_user->execute([$username, $hashed_password, $nama_lengkap]);
                $user_id = $pdo->lastInsertId();

                // 2. Insert Guru
                $stmt_guru = $pdo->prepare("INSERT INTO guru (user_id, nuptk, nama_lengkap, mata_pelajaran, is_wali_kelas, kelas_wali, password_plain) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt_guru->execute([$user_id, $nuptk, $nama_lengkap, $mata_pelajaran, $is_wali_kelas, $kelas_wali, $password_plain]);

                $pdo->commit();
                header("Location: ../data_guru.php");
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
    <title>Tambah Guru - Admin</title>
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <script>
        function toggleWaliKelas() {
            var checkbox = document.getElementById('is_wali_kelas');
            var div = document.getElementById('wali_kelas_input');
            if (checkbox.checked) {
                div.style.display = 'block';
            } else {
                div.style.display = 'none';
            }
        }
    </script>
</head>

<body>
    <div class="main-content" style="margin-left: 0; width: 100%; max-width: 600px; margin: 2rem auto;">
        <div class="card">
            <h2><?= $is_edit ? 'Edit Data Guru' : 'Tambah Guru Baru' ?></h2>
            <p class="subtitle"><?= $is_edit ? 'Perbarui data guru' : 'Buat akun dan data guru baru' ?></p>

            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>NUPTK *</label>
                <input type="number" name="nuptk" required placeholder="16 digit NUPTK" maxlength="16" value="<?= $is_edit ? htmlspecialchars($data['nuptk']) : '' ?>">

                <label>Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" placeholder="Contoh: Budi Santoso, S.Pd." required value="<?= $is_edit ? htmlspecialchars($data['nama_lengkap']) : '' ?>">

                <label>Mata Pelajaran *</label>
                <select name="mata_pelajaran" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    <?php
                    $mapel_list = ["Matematika", "Bahasa Indonesia", "Bahasa Inggris", "Fisika", "Kimia", "Biologi", "Sejarah", "Geografi", "Ekonomi", "Sosiologi", "Penjaskes", "Seni Budaya", "TIK", "PKn", "PAI"];
                    foreach ($mapel_list as $m) :
                        $selected = ($is_edit && $data['mata_pelajaran'] == $m) ? 'selected' : '';
                        echo "<option value=\"$m\" $selected>$m</option>";
                    endforeach;
                    ?>
                </select>

                <div style="margin: 15px 0;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_wali_kelas" id="is_wali_kelas" value="1" onclick="toggleWaliKelas()" style="width: auto; margin: 0;" <?= ($is_edit && $data['is_wali_kelas']) ? 'checked' : '' ?>>
                        Apakah Wali Kelas?
                    </label>
                </div>

                <div id="wali_kelas_input" style="display: <?= ($is_edit && $data['is_wali_kelas']) ? 'block' : 'none' ?>;">
                    <label>Wali Kelas Untuk (Contoh: X IPA 1)</label>
                    <input type="text" name="kelas_wali" placeholder="Masukkan nama kelas" value="<?= $is_edit ? htmlspecialchars($data['kelas_wali'] ?? '') : '' ?>">
                </div>

                <?php if (!$is_edit): ?>
                    <div style="background: #e8f5e9; padding: 10px; border-radius: 6px; margin: 10px 0; font-size: 0.9em; color: #2e7d32;">
                        <strong>Info Akun Otomatis:</strong><br>
                        Username: <em>(random: guru####)</em><br>
                        Password Default: <strong>123456</strong>
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="../data_guru.php" style="flex: 1; padding: 12px; text-align: center; border: 1px solid #ddd; border-radius: 8px; color: #666;">Batal</a>
                    <button type="submit" style="flex: 2;"><?= $is_edit ? 'Simpan Perubahan' : 'Simpan Data' ?></button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
