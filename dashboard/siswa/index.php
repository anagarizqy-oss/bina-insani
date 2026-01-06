<?php
// dashboard/siswa/index.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];

// Get Student ID
$stmt = $pdo->prepare("SELECT id, nis, kelas, jurusan, nomor_kelas FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$siswa_id = $siswa['id'];

// Quick Stats: Total Kehadiran
$stmt_hadir = $pdo->prepare("SELECT COUNT(*) FROM presensi WHERE siswa_id = ? AND status = 'Hadir'");
$stmt_hadir->execute([$siswa_id]);
$total_hadir = $stmt_hadir->fetchColumn();

// Quick Stats: Total Alpha
$stmt_alpha = $pdo->prepare("SELECT COUNT(*) FROM presensi WHERE siswa_id = ? AND status = 'Alpha'");
$stmt_alpha->execute([$siswa_id]);
$total_alpha = $stmt_alpha->fetchColumn();

// Quick Stats: SPP Terakhir
$stmt_spp = $pdo->prepare("SELECT bulan, status FROM spp WHERE siswa_id = ? ORDER BY id DESC LIMIT 1");
$stmt_spp->execute([$siswa_id]);
$last_spp = $stmt_spp->fetch();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            <span>Selamat Datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></span>
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Dashboard Overview</h2>

        <div class="card">
            <p><strong>NIS:</strong> <?= htmlspecialchars($siswa['nis']) ?> |
                <strong>Kelas:</strong> <?= htmlspecialchars($siswa['kelas'] . ' ' . $siswa['jurusan'] . ' ' . $siswa['nomor_kelas']) ?>
            </p>
        </div>
        <br>

        <div class="card-grid">
            <div class="card">
                <h3><i class="fas fa-check-circle" style="color: var(--success-color);"></i> Kehadiran</h3>
                <p>Total Hadir</p>
                <div class="stat-value"><?= $total_hadir ?></div>
                <small>Hari</small>
            </div>

            <div class="card">
                <h3><i class="fas fa-times-circle" style="color: var(--danger-color);"></i> Absen (Alpha)</h3>
                <p>Total Alpha</p>
                <div class="stat-value" style="color: var(--danger-color);"><?= $total_alpha ?></div>
                <small>Hari</small>
            </div>

            <div class="card">
                <h3><i class="fas fa-wallet" style="color: var(--warning-color);"></i> Status SPP Terakhir</h3>
                <?php if ($last_spp): ?>
                    <p>Bulan: <?= htmlspecialchars($last_spp['bulan']) ?></p>
                    <div class="badge <?= $last_spp['status'] == 'Lunas' ? 'success' : 'danger' ?>" style="display:inline-block; margin-top:5px;">
                        <?= $last_spp['status'] ?>
                    </div>
                <?php else: ?>
                    <p>Belum ada data SPP</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h3>Informasi Sekolah</h3>
            <p>Selamat datang di Dashboard Siswa SMA Bina Insani. Gunakan menu di sidebar untuk melihat detail presensi, nilai akademik, dan status pembayaran SPP Anda.</p>
        </div>
    </div>
</div>

</body>

</html>