<?php
// dashboard/admin/kelola_galeri.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['admin']);

$message = '';
if (isset($_SESSION['success_message'])) {
    $message = "<div class='alert success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $message = "<div class='alert error'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}

// Hapus Foto
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    // Ambil path foto lama
    $stmt = $pdo->prepare("SELECT url_foto FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row && !empty($row['url_foto'])) {
        $file_path = '../../' . $row['url_foto'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $pdo->prepare("DELETE FROM galeri WHERE id = ?")->execute([$id]);
    $_SESSION['success_message'] = "Foto berhasil dihapus dari galeri.";
    header("Location: kelola_galeri.php");
    exit;
}

// Ambil data galeri
$galeri_list = $pdo->query("SELECT * FROM galeri ORDER BY tanggal_foto DESC, created_at DESC");
$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - Admin SMA BINA INSANI</title>
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
            <a href="kelola_ekstrakurikuler.php" class="menu-item">
                <i class="fas fa-futbol"></i> <span>Ekstrakurikuler</span>
            </a>
            <a href="masukan.php" class="menu-item">
                <i class="fas fa-envelope-open-text"></i> <span>Masukan & Saran</span>
            </a>
            <a href="kelola_galeri.php" class="menu-item active">
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
        <header style="margin-bottom: 2rem;">
            <h2>Kelola Galeri</h2>
            <p class="subtitle">Manajemen galeri foto dan album kegiatan sekolah</p>
        </header>

        <!-- Form Tambah Foto -->
        <div class="card" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Tambah Foto Baru</h3>
            <?= $message ?>
            <form method="POST" action="function_admin/simpan_galeri.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Foto Kegiatan</label>
                        <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <small style="color: #666;">Format: JPG, PNG, WEBP. Maks: 5MB.</small>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tanggal Pengambilan</label>
                        <input type="date" name="tanggal_foto" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3" placeholder="Contoh: Kegiatan Upacara Bendera Senin..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit;"></textarea>
                </div>

                <button type="submit" name="tambah_foto" style="background: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-upload"></i> Upload Foto
                </button>
            </form>
        </div>

        <!-- Daftar Galeri -->
        <div class="card">
            <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Daftar Foto Galeri</h3>
            <?php if ($galeri_list->rowCount() > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                    <?php while ($row = $galeri_list->fetch()): ?>
                        <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <div style="height: 150px; overflow: hidden;">
                                <img src="../../<?= htmlspecialchars($row['url_foto']) ?>" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="padding: 1rem;">
                                <p style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">
                                    <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['tanggal_foto'])) ?>
                                </p>
                                <p style="font-size: 0.95rem; margin-bottom: 1rem; line-height: 1.4; height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?= htmlspecialchars($row['deskripsi']) ?>
                                </p>
                                <a href="?hapus=<?= $row['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus foto ini?')"
                                    style="display: block; text-align: center; background: #ffebee; color: #c62828; padding: 6px; border-radius: 4px; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">Belum ada foto di galeri.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>