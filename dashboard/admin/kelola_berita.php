<?php
// dashboard/admin/berita.php
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

// Tambah berita
// Tambah berita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_berita'])) {

    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error_message'] = "Permintaan tidak valid.";
        header("Location: kelola_berita.php");
        exit;
    }

    $judul = clean($_POST['judul']);
    $isi   = $_POST['isi'];
    $cover_path = null;

    // Upload cover
    if (!empty($_FILES['cover']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $_SESSION['error_message'] = "Format cover tidak valid.";
            header("Location: kelola_berita.php");
            exit;
        }

        if ($_FILES['cover']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error_message'] = "Ukuran cover maksimal 2MB.";
            header("Location: kelola_berita.php");
            exit;
        }

        $dir = '../../assets/img/cover_berita/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $filename = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($_FILES['cover']['tmp_name'], $dir . $filename);
        // Simpan path relative untuk frontend (tanpa ../..)
        $cover_path = 'assets/img/cover_berita/' . $filename;
    }

    if (empty($judul) || empty($isi)) {
        $_SESSION['error_message'] = "Judul dan isi wajib diisi.";
        header("Location: kelola_berita.php");
        exit;
    }

    // INSERT
    $stmt = $pdo->prepare(
        "INSERT INTO berita (judul, isi, cover) VALUES (?, ?, ?)"
    );
    $stmt->execute([$judul, $isi, $cover_path]);

    // FLASH MESSAGE + REDIRECT (INI KUNCI)
    $_SESSION['success_message'] = "Berita berhasil dipublikasikan.";
    header("Location: kelola_berita.php");
    exit;
}

// Hapus berita
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    // Ambil path cover lama
    $stmt = $pdo->prepare("SELECT cover FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row && !empty($row['cover'])) {
        $file_path = '../../' . $row['cover'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $pdo->prepare("DELETE FROM berita WHERE id = ?")->execute([$id]);
    $_SESSION['success_message'] = "Berita berhasil dihapus.";
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
    <link rel="stylesheet" href="../../assets/css/admin.css">
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
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Judul Berita</label>
                    <input type="text" name="judul" placeholder="Contoh: Upacara Hari Pahlawan" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Cover Berita (Opsional)</label>
                    <input type="file" name="cover" accept=".jpg,.jpeg,.png,.webp" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #666;">Format: JPG, PNG, WEBP. Maks: 2MB.</small>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Isi Berita</label>
                    <textarea name="isi" rows="5" placeholder="Tulis isi berita di sini..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
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
                                <th style="padding: 12px; border-bottom: 2px solid #ddd; width: 100px;">Cover</th>
                                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Judul</th>
                                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $berita_list->fetch()): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; width: 150px;"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                    <td style="padding: 12px;">
                                        <?php if (!empty($row['cover'])): ?>
                                            <img src="../../<?= htmlspecialchars($row['cover']) ?>" alt="Cover" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <?php else: ?>
                                            <span style="color: #999; font-size: 0.8em;">No Image</span>
                                        <?php endif; ?>
                                    </td>
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
            height: 400,

            plugins: 'lists link image media table code preview',
            toolbar: `
        undo redo |
        fontfamily fontsize |
        bold italic underline |
        alignleft aligncenter alignright |
        bullist numlist |
        link image media |
        preview code
    `,

            automatic_uploads: true,

            images_upload_handler: function(blobInfo) {

                return new Promise(function(resolve, reject) {

                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    fetch('function_admin/upload_image_berita.php', {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.location) {
                                resolve(result.location); // WAJIB resolve URL
                            } else {
                                reject(result.error || 'Upload gagal');
                            }
                        })
                        .catch(() => {
                            reject('Kesalahan koneksi server');
                        });

                });
            }
        });
    </script>



</body>

</html>