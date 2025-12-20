<?php
// dashboard/admin/data_guru.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['admin']);

// Fetch data guru
// Assuming table name is 'guru'
$guru_list = [];
try {
    $stmt = $pdo->query("SELECT g.*, u.username FROM guru g JOIN users u ON g.user_id = u.id ORDER BY g.nama_lengkap ASC");
    $guru_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Gagal mengambil data guru: " . $e->getMessage();
}
// Session Messages
if (isset($_SESSION['success_message'])) {
    $error = null; // Clear previous error
    $success_msg = $_SESSION['success_message']; // Use distinct var
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $pdo->beginTransaction();

        // Get user_id first
        $stmt_get = $pdo->prepare("SELECT user_id FROM guru WHERE id = ?");
        $stmt_get->execute([$id]);
        $user_id = $stmt_get->fetchColumn();

        if ($user_id) {
            // Delete from guru
            $pdo->prepare("DELETE FROM guru WHERE id = ?")->execute([$id]);
            // Delete from users
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);

            $pdo->commit();
            header("Location: data_guru.php");
            exit;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Gagal menghapus data: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - Admin SMA BINA INSANI</title>
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
            <a href="data_guru.php" class="menu-item active">
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
                <h2>Data Guru</h2>
                <p class="subtitle">Kelola data guru dan staf pengajar</p>
            </div>
            <div class="header-actions" style="display: flex; gap: 10px;">
                <!-- Export -->
                <a href="function_admin/export_guru.php" class="btn-export" style="background: #4caf50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <!-- Import -->
                <button onclick="document.getElementById('importModal').style.display='flex'" class="btn-import" style="background: #ff9800; color: white; padding: 10px 15px; border: none; border-radius: 6px; font-weight: 500; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-file-upload"></i> Import Excel
                </button>
                <a href="function_admin/add_guru.php" class="btn-add" style="background: #2575fc; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
        </header>

        <?php if (isset($success_msg)): ?>
            <div class="alert success" style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 6px; margin-bottom: 20px;">
                <?= $success_msg ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($guru_list) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NUPTK</th>
                                <th>Nama Lengkap</th>
                                <th>Mata Pelajaran</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($guru_list as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nuptk'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['nama_lengkap'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['mata_pelajaran'] ?? '-') ?></td>
                                    <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($row['password_plain']) ?></code></td>
                                    <td>
                                        <a href="function_admin/add_guru.php?id=<?= $row['id'] ?>" style="color: #2575fc; margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data guru ini? Akun login juga akan dihapus.')" style="color: #e53935;"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">Belum ada data guru.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;">
        <div style="background: white; padding: 2rem; border-radius: 12px; width: 400px; max-width: 90%;">
            <h3 style="margin-bottom: 1rem;">Import Data Guru (Excel/CSV)</h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">
                Format: NUPTK, Nama, Mapel, IsWali(1/0), KelasWali.<br>
                Mendukung file .xlsx, .xls, dan .csv.<br>
                Baris pertama header (akan diabaikan).
            </p>
            <form action="function_admin/import_guru.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="file_excel" accept=".csv, .xlsx, .xls" required style="margin-bottom: 1rem; width: 100%;">
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="padding: 8px 15px; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 8px 15px; background: #2575fc; color: white; border: none; border-radius: 6px; cursor: pointer;">Upload</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
