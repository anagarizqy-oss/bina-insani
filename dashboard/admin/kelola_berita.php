<?php
// dashboard/admin/berita.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['admin']);

$message = '';

// Tambah berita
if ($_POST && isset($_POST['tambah_berita'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Permintaan tidak valid.</div>";
    } else {
        $judul = clean($_POST['judul']);
        $isi = clean($_POST['isi']);
        if (!empty($judul) && !empty($isi)) {
            $stmt = $pdo->prepare("INSERT INTO berita (judul, isi) VALUES (?, ?)");
            $stmt->execute([$judul, $isi]);
            $message = "<div class='alert success'>Berita berhasil ditambahkan!</div>";
        } else {
            $message = "<div class='alert error'>Judul dan isi tidak boleh kosong.</div>";
        }
    }
}

// Hapus berita
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $pdo->prepare("DELETE FROM berita WHERE id = ?")->execute([$id]);
    header("Location: kelola_berita.php");
    exit;
}

$berita_list = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC");
$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/admin.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tiny.cloud/1/73g6ti6vu2fak6uikd05gzldad4bpmzf7i39m09kw1qa7oqb/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

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
            <a href="kelola_berita.php" class="menu-item active">
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
            <h2>Kelola Berita Sekolah</h2>
            <p class="subtitle">Publikasikan berita dan informasi terbaru sekolah</p>
        </header>

        <!-- Form Tambah Berita -->
        <div class="card" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Tambah Berita Baru</h3>
            <?= $message ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Judul Berita</label>
                    <input type="text" name="judul" placeholder="Contoh: Upacara Hari Pahlawan" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Isi Berita</label>
                    <textarea name="isi" rows="5" placeholder="Tulis isi berita di sini..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                </div>

                <button type="submit" name="tambah_berita" style="background: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">Publikasikan Berita</button>
            </form>
        </div>

        <!-- Daftar Berita -->
        <div class="card">
            <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Daftar Berita</h3>
            <?php if ($berita_list->rowCount() > 0): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; text-align: left;">
                                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Tanggal</th>
                                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Judul</th>
                                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $berita_list->fetch()): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; width: 150px;"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                    <td style="padding: 12px;"><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                    <td style="padding: 12px; width: 100px;">
                                        <a href="?hapus=<?= $row['id'] ?>"
                                            onclick="return confirm('Hapus berita ini?')"
                                            style="color: #e53935; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">Belum ada berita.</p>
            <?php endif; ?>
        </div>
    </div>
<script>
tinymce.init({
    selector: 'textarea[name="isi"]',
    height: 350,
    menubar: false,

    plugins: [
        'lists link image table code preview'
    ],

    toolbar: `
        undo redo |
        fontfamily fontsize |
        bold italic underline |
        alignleft aligncenter alignright |
        bullist numlist |
        link image |
        preview code
    `,

    font_family_formats: `
        Arial=arial,helvetica,sans-serif;
        Times New Roman=times new roman,times;
        Georgia=georgia,serif;
        Verdana=verdana,geneva,sans-serif;
        Tahoma=tahoma,arial,sans-serif;
        Courier New=courier new,courier,monospace
    `,

    fontsize_formats: '10px 12px 14px 16px 18px 24px 36px',

    content_style: `
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
    `
});
</script>

</body>

</html>