<?php
// dashboard/admin/kelola_ekstrakurikuler.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['admin']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ekstrakurikuler - Admin SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/admin.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Admin Panel</h2>
        </div>

        <div class="sidebar-menu">
            <a href="index.php" class="menu-item">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
            <a href="data_siswa.php" class="menu-item">
                <i class="fas fa-user-graduate"></i> <span>Data Siswa</span>
            </a>
            <a href="data_guru.php" class="menu-item">
                <i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span>
            </a>
            <a href="kelola_berita.php" class="menu-item">
                <i class="fas fa-newspaper"></i> <span>Kelola Berita</span>
            </a>
            <a href="kelola_ekstrakurikuler.php" class="menu-item active">
                <i class="fas fa-futbol"></i> <span>Ekstrakurikuler</span>
            </a>
            <a href="masukan.php" class="menu-item">
                <i class="fas fa-envelope-open-text"></i> <span>Masukan & Saran</span>
            </a>
            <a href="profil.php" class="menu-item">
                <i class="fas fa-user"></i> <span>Profil Saya</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <a href="../../logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header style="margin-bottom: 2rem;">
            <h2>Kelola Ekstrakurikuler</h2>
            <p class="subtitle">Manajemen data ekstrakurikuler sekolah</p>
        </header>

        <div class="card">
            <p style="text-align: center; color: #666; padding: 2rem;">Halaman pengelolaan ekstrakurikuler (Konten dapat ditambahkan di sini).</p>
        </div>
    </div>

</body>

</html>