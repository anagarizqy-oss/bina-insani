<?php
// dashboard/guru/presensi.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['guru']);

$message = '';

// Ambil daftar siswa
$siswa_list = $pdo->query("SELECT s.id, u.nama_lengkap FROM siswa s JOIN users u ON s.user_id = u.id ORDER BY u.nama_lengkap");

// Simpan presensi
if ($_POST && isset($_POST['simpan_presensi'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Permintaan tidak valid.</div>";
    } else {
        $siswa_id = (int)$_POST['siswa_id'];
        $tanggal = $_POST['tanggal'];
        $status = $_POST['status'];

        if ($siswa_id > 0 && !empty($tanggal) && in_array($status, ['Hadir', 'Sakit', 'Izin', 'Alpha'])) {
            // Cek apakah presensi untuk tanggal ini sudah ada â†’ update
            $cek = $pdo->prepare("SELECT id FROM presensi WHERE siswa_id = ? AND tanggal = ?");
            $cek->execute([$siswa_id, $tanggal]);
            if ($cek->rowCount() > 0) {
                $stmt = $pdo->prepare("UPDATE presensi SET status = ? WHERE siswa_id = ? AND tanggal = ?");
                $stmt->execute([$status, $siswa_id, $tanggal]);
                $message = "<div class='alert success'>Presensi berhasil diperbarui!</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO presensi (siswa_id, tanggal, status) VALUES (?, ?, ?)");
                $stmt->execute([$siswa_id, $tanggal, $status]);
                $message = "<div class='alert success'>Presensi berhasil disimpan!</div>";
            }
        } else {
            $message = "<div class='alert error'>Data tidak valid.</div>";
        }
    }
}

// Ambil presensi 7 hari terakhir untuk ditampilkan
$tujuh_hari_lalu = date('Y-m-d', strtotime('-7 days'));
$presensi_terbaru = $pdo->prepare("
    SELECT u.nama_lengkap, p.tanggal, p.status 
    FROM presensi p
    JOIN siswa s ON p.siswa_id = s.id
    JOIN users u ON s.user_id = u.id
    WHERE p.tanggal >= ?
    ORDER BY p.tanggal DESC, u.nama_lengkap
");
$presensi_terbaru->execute([$tujuh_hari_lalu]);
$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Presensi - Guru</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <h1>SMA BINA INSANI</h1>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="nilai.php">Nilai</a>
            <a href="presensi.php" style="border-bottom: 2px solid white;">Presensi</a>
            <?php
            $is_wali = $pdo->prepare("SELECT is_wali_kelas FROM guru WHERE user_id = ?");
            $is_wali->execute([$_SESSION['user_id']]);
            if ($is_wali->fetchColumn()): ?>
                <a href="spp.php">SPP</a>
            <?php endif; ?>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Catat Kehadiran Siswa</h2>

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

                <label>Tanggal</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>

                <label>Status Kehadiran</label>
                <select name="status" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Alpha">Alpha (Tidak Hadir)</option>
                </select>

                <button type="submit" name="simpan_presensi">Simpan Presensi</button>
            </form>
        </div>

        <!-- Tampilkan Presensi Terbaru -->
        <div class="card">
            <h3>Presensi 7 Hari Terakhir</h3>
            <?php $data = $presensi_terbaru->fetchAll(); ?>
            <?php if (count($data) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    $color = match($status) {
                                        'Hadir' => '#4caf50',
                                        'Sakit' => '#ff9800',
                                        'Izin' => '#2196f3',
                                        default => '#f44336'
                                    };
                                    echo "<span style='color:$color; font-weight:bold;'>$status</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #888;">Belum ada presensi yang dicatat.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
