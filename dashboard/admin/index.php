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

// Fetch Login Logs
$logs = [];
try {
    $stmt_logs = $pdo->query("SELECT * FROM login_logs ORDER BY login_time DESC LIMIT 10");
    $logs = $stmt_logs->fetchAll();
} catch (PDOException $e) {
    // Handle error or leave empty
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMA BINA INSANI</title>
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
            <a href="index.php" class="menu-item active">
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
            <a href="kelola_ekstrakurikuler.php" class="menu-item">
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

        <!-- Login Activity Log -->
        <div class="card" style="margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Log Aktivitas Login Baru</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; text-align: left;">
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Waktu Login</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Username</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Role</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">IP Address</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Status</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Waktu Logout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs) && count($logs) > 0): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px;"><?= date('d M Y H:i', strtotime($log['login_time'])) ?></td>
                                    <td style="padding: 12px;"><strong><?= htmlspecialchars($log['username']) ?></strong></td>
                                    <td style="padding: 12px;"><span style="text-transform: capitalize; background: #e3f2fd; color: #1565c0; padding: 2px 8px; border-radius: 4px; font-size: 0.85em;"><?= htmlspecialchars($log['role']) ?></span></td>
                                    <td style="padding: 12px; color: #666;"><?= htmlspecialchars($log['ip_address']) ?></td>
                                    <td style="padding: 12px;">
                                        <?php if ($log['is_active']): ?>
                                            <span style="background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">Aktif</span>
                                        <?php else: ?>
                                            <span style="background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">Logout</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px; color: #666;"><?= $log['logout_time'] ? date('H:i', strtotime($log['logout_time'])) : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #666; padding: 20px;">Belum ada aktivitas login.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>