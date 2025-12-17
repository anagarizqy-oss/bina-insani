<?php
// dashboard/guru/spp.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['guru']);

// Cek apakah guru ini wali kelas
$stmt = $pdo->prepare("SELECT is_wali_kelas, kelas_wali FROM guru WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$guru = $stmt->fetch();

if (!$guru || !$guru['is_wali_kelas']) {
    die("Anda bukan wali kelas. Akses ditolak.");
}

$kelas_wali = $guru['kelas_wali'];
$message = '';

// Ambil siswa di kelas wali
$siswa_list = $pdo->prepare("
    SELECT s.id, u.nama_lengkap 
    FROM siswa s 
    JOIN users u ON s.user_id = u.id 
    WHERE s.kelas = ?
    ORDER BY u.nama_lengkap
");
$siswa_list->execute([$kelas_wali]);

// Simpan SPP
if ($_POST && isset($_POST['simpan_spp'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Permintaan tidak valid.</div>";
    } else {
        $siswa_id = (int)$_POST['siswa_id'];
        $bulan = clean($_POST['bulan']);
        $status = $_POST['status'];

        if ($siswa_id > 0 && !empty($bulan) && in_array($status, ['Lunas', 'Belum'])) {
            // Cek apakah sudah ada → update
            $cek = $pdo->prepare("SELECT id FROM spp WHERE siswa_id = ? AND bulan = ?");
            $cek->execute([$siswa_id, $bulan]);
            if ($cek->rowCount() > 0) {
                $stmt = $pdo->prepare("UPDATE spp SET status = ? WHERE siswa_id = ? AND bulan = ?");
                $stmt->execute([$status, $siswa_id, $bulan]);
                $message = "<div class='alert success'>Status SPP berhasil diperbarui!</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO spp (siswa_id, bulan, status) VALUES (?, ?, ?)");
                $stmt->execute([$siswa_id, $bulan, $status]);
                $message = "<div class='alert success'>Status SPP berhasil disimpan!</div>";
            }
        } else {
            $message = "<div class='alert error'>Data tidak valid.</div>";
        }
    }
}

// Daftar bulan
$bulan_list = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Ambil data SPP siswa di kelas ini
$spp_data = $pdo->prepare("
    SELECT u.nama_lengkap, s.bulan, s.status, s.id as spp_id
    FROM spp s
    JOIN siswa si ON s.siswa_id = si.id
    JOIN users u ON si.user_id = u.id
    WHERE si.kelas = ?
    ORDER BY u.nama_lengkap, s.bulan
");
$spp_data->execute([$kelas_wali]);
$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola SPP - Wali Kelas</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <h1>SMA BINA INSANI</h1>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="nilai.php">Nilai</a>
            <a href="presensi.php">Presensi</a>
            <a href="spp.php" style="border-bottom: 2px solid white;">SPP</a>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Kelola SPP – Kelas <?= htmlspecialchars($kelas_wali) ?></h2>
        <p class="subtitle">Wali Kelas: <?= $_SESSION['nama'] ?></p>

        <div class="card">
            <?= $message ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <label>Pilih Siswa</label>
                <select name="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php while ($siswa = $siswa_list->fetch()): ?>
                        <option value="<?= $siswa['id'] ?>"><?= htmlspecialchars($siswa['nama_lengkap']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label>Bulan</label>
                <select name="bulan" required>
                    <option value="">-- Pilih Bulan --</option>
                    <?php foreach ($bulan_list as $bln): ?>
                        <option value="<?= $bln ?>"><?= $bln ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Status Pembayaran</label>
                <select name="status" required>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum">Belum Lunas</option>
                </select>

                <button type="submit" name="simpan_spp">Simpan Status SPP</button>
            </form>
        </div>

        <!-- Tabel SPP -->
        <div class="card">
            <h3>Daftar SPP Siswa Kelas <?= htmlspecialchars($kelas_wali) ?></h3>
            <?php $data = $spp_data->fetchAll(); ?>
            <?php if (count($data) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Bulan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['bulan']) ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Lunas'): ?>
                                        <span style="color: #4caf50; font-weight: bold;">✓ Lunas</span>
                                    <?php else: ?>
                                        <span style="color: #f44336; font-weight: bold;">✕ Belum</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #888;">Belum ada data SPP.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>