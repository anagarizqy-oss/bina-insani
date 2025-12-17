<?php
// dashboard/guru/index.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

// Cek apakah guru ini wali kelas
$stmt = $pdo->prepare("SELECT is_wali_kelas FROM guru WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$is_wali = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <nav class="navbar">
    <div class="navbar-left">
        <img src="../../assets/logo-navbar.png" alt="Logo" class="navbar-logo">
        <h1>SMA BINA INSANI</h1>
    </div>
    <div class="nav-links">
        <a href="index.php">Beranda</a>
        <a href="nilai.php">Nilai</a>
        <a href="presensi.php">Presensi</a>
        <!-- ... -->
    </div>
</nav>

    <div class="dashboard">
        <h2>Selamat Datang, <?= $_SESSION['nama'] ?>!</h2>
        <p class="subtitle">Guru - SMA Bina Insani Wonogiri</p>

        <div class="card">
            <h3>Fitur Tersedia</h3>
            <ul style="list-style: none; padding: 0; margin-top: 1rem;">
                <li style="margin: 0.8rem 0;">
                    <a href="nilai.php" style="color: #2575fc; text-decoration: none; font-weight: bold;">➤ Input Nilai Siswa</a>
                </li>
                <li style="margin: 0.8rem 0;">
                    <a href="presensi.php" style="color: #2575fc; text-decoration: none; font-weight: bold;">➤ Catat Kehadiran Siswa</a>
                </li>
                <?php if ($is_wali): ?>
                <li style="margin: 0.8rem 0;">
                    <a href="spp.php" style="color: #2575fc; text-decoration: none; font-weight: bold;">➤ Kelola Pembayaran SPP (Wali Kelas)</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>