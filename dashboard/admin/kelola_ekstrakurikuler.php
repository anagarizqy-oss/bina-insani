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
    <link rel="stylesheet" href="../../assets/css/admin.css">
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
            <a href="kelola_galeri.php" class="menu-item">
                <i class="fas fa-images"></i> <span>Kelola Galeri</span>
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
                <h2>Kelola Ekstrakurikuler</h2>
                <p class="subtitle">Manajemen data ekstrakurikuler sekolah</p>
            </div>
            <a href="function_admin/add_ekskul.php" class="btn-add" style="background: #2575fc; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </header>

        <?php
        // Fetch Data
        try {
            $stmt = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY nama_ekskul ASC");
            $ekskul_list = $stmt->fetchAll();
        } catch (PDOException $e) {
            $error = "gagal mengambil data: " . $e->getMessage();
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            try {
                $pdo->prepare("DELETE FROM ekstrakurikuler WHERE id_ekskul = ?")->execute([$id]);
                echo "<script>alert('Data berhasil dihapus'); window.location='kelola_ekstrakurikuler.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Gagal menghapus data');</script>";
            }
        }
        ?>

        <div class="card">
            <?php if (count($ekskul_list) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ekskul</th>
                                <th>Guru Pembimbing</th>
                                <th>Ketua</th>
                                <th>Wakil Ketua</th>
                                <th>Anggota</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($ekskul_list as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_ekskul']) ?></td>
                                    <td><?= htmlspecialchars($row['guru_pembimbing']) ?></td>
                                    <td><?= htmlspecialchars($row['ketua']) ?></td>
                                    <td><?= htmlspecialchars($row['wakil_ketua']) ?></td>
                                    <td><?= htmlspecialchars($row['jumlah_anggota']) ?></td>
                                    <td>
                                        <a href="function_admin/add_ekskul.php?id=<?= $row['id_ekskul'] ?>" style="color: #2575fc; margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                        <a href="?delete=<?= $row['id_ekskul'] ?>" onclick="return confirm('Hapus data ekskul ini?')" style="color: #e53935;"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">Belum ada data ekstrakurikuler.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>