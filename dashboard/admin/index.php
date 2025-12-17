<?php
// dashboard/admin/index.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['admin']);

// Helper function to safely count rows
function countRows($pdo, $table)
{
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0; // Return 0 if table doesn't exist or query fails
    }
}

// Fetch counts
$count_siswa = countRows($pdo, 'siswa');
$count_guru = countRows($pdo, 'guru');
$count_berita = countRows($pdo, 'berita');
$count_ekskul = countRows($pdo, 'ekstrakurikuler');

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/admin.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Admin Panel</h2>
        </div>

        <div class="sidebar-menu">
            <a href="index.php" class="menu-item active">
                <span>🏠</span> <span>Dashboard</span>
            </a>
            <a href="data_siswa.php" class="menu-item">
                <span>🎓</span> <span>Data Siswa</span>
            </a>
            <a href="data_guru.php" class="menu-item">
                <span>👨‍🏫</span> <span>Data Guru</span>
            </a>
            <a href="berita.php" class="menu-item">
                <span>📰</span> <span>Kelola Berita</span>
            </a>
            <a href="ekstrakurikuler.php" class="menu-item">
                <span>⚽</span> <span>Ekstrakurikuler</span>
            </a>
            <a href="profil.php" class="menu-item">
                <span>👤</span> <span>Profil Saya</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <a href="../../logout.php" class="logout-btn">
                <span>🚪 Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header style="margin-bottom: 2rem;">
            <h2>Halo, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</h2>
            <p class="subtitle">Selamat datang di Panel Admin SMA Bina Insani</p>
        </header>

        <!-- Widgets Grid -->
        <div class="widgets-grid">

            <!-- Widget Siswa -->
            <div class="widget">
                <div>
                    <div class="widget-title">Total Siswa</div>
                    <div class="widget-count"><?= $count_siswa ?></div>
                </div>
                <a href="data_siswa.php" class="widget-link">Lihat Detail →</a>
            </div>

            <!-- Widget Guru -->
            <div class="widget">
                <div>
                    <div class="widget-title">Total Guru</div>
                    <div class="widget-count"><?= $count_guru ?></div>
                </div>
                <a href="data_guru.php" class="widget-link">Lihat Detail →</a>
            </div>

            <!-- Widget Berita -->
            <div class="widget">
                <div>
                    <div class="widget-title">Berita Terbit</div>
                    <div class="widget-count"><?= $count_berita ?></div>
                </div>
                <a href="berita.php" class="widget-link">Kelola Berita →</a>
            </div>

            <!-- Widget Ekskul -->
            <div class="widget">
                <div>
                    <div class="widget-title">Ekstrakurikuler</div>
                    <div class="widget-count"><?= $count_ekskul ?></div>
                </div>
                <a href="ekstrakurikuler.php" class="widget-link">Kelola Ekskul →</a>
            </div>

        </div>

    </div>

</body>

</html>