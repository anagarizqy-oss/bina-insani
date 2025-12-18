<?php
// dashboard/admin/data_siswa.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['admin']);

// Fetch data siswa
// Assuming table name is 'siswa'
$siswa_list = [];
try {
    $stmt = $pdo->query("SELECT s.*, u.username FROM siswa s JOIN users u ON s.user_id = u.id ORDER BY s.nama_lengkap ASC");
    $siswa_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Gagal mengambil data siswa: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Admin SMA BINA INSANI</title>
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
            <a href="data_siswa.php" class="menu-item active">
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
        <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Data Siswa</h2>
                <p class="subtitle">Kelola data siswa SMA Bina Insani</p>
            </div>
            <a href="function_admin/add_siswa.php" class="widget-link" style="background: #2575fc; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">+ Tambah Siswa</a>
        </header>

        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($siswa_list) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($siswa_list as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['nama_lengkap'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars(($row['kelas'] ?? '') . ' ' . ($row['jurusan'] ?? '') . ' ' . ($row['nomor_kelas'] ?? '')) ?></td>
                                    <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($row['password_plain']) ?></code></td>
                                    <td>
                                        <a href="#" style="color: #2575fc; margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                        <a href="#" style="color: #e53935;"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">Belum ada data siswa.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>