<?php
// dashboard/admin/kelola_kelas.php
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

// Tambah Kelas
if (isset($_POST['tambah_kelas'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Token CSRF tidak valid.</div>";
    } else {
        $nama_kelas = strtoupper(clean($_POST['nama_kelas']));

        if (empty($nama_kelas)) {
            $message = "<div class='alert error'>Nama kelas tidak boleh kosong.</div>";
        } elseif (!preg_match('/^(X|XI|XII)\s(IPA|IPS|BAHASA)\s[0-9]+$/', $nama_kelas)) {
            $message = "<div class='alert error'>Format salah. Gunakan: Tingkat Jurusan Nomor (Contoh: X IPA 1).</div>";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
                $stmt->execute([$nama_kelas]);
                $_SESSION['success_message'] = "Kelas berhasil ditambahkan.";
                header("Location: kelola_kelas.php");
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Duplicate entry
                    $message = "<div class='alert error'>Kelas sudah ada.</div>";
                } else {
                    $message = "<div class='alert error'>Gagal menambah kelas.</div>";
                }
            }
        }
    }
}

// Hapus Kelas
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    try {
        $stmt = $pdo->prepare("DELETE FROM kelas WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success_message'] = "Kelas berhasil dihapus.";
        header("Location: kelola_kelas.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Gagal menghapus kelas.";
        header("Location: kelola_kelas.php");
        exit;
    }
}

// Edit Kelas
if (isset($_POST['edit_kelas'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Token CSRF tidak valid.</div>";
    } else {
        $id = (int)$_POST['id'];
        $nama_kelas = strtoupper(clean($_POST['nama_kelas']));

        if (!preg_match('/^(X|XI|XII)\s(IPA|IPS|BAHASA)\s[0-9]+$/', $nama_kelas)) {
            $_SESSION['error_message'] = "Format salah. Gunakan: Tingkat Jurusan Nomor (Contoh: X IPA 1).";
            header("Location: kelola_kelas.php");
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE kelas SET nama_kelas = ? WHERE id = ?");
            $stmt->execute([$nama_kelas, $id]);
            $_SESSION['success_message'] = "Kelas berhasil diperbarui.";
            header("Location: kelola_kelas.php");
            exit;
        } catch (PDOException $e) {
            $message = "<div class='alert error'>Gagal memperbarui kelas. Nama mungkin sudah ada.</div>";
        }
    }
}

// Ambil data kelas
$kelas_list = $pdo->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas - Admin SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
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
            <a href="index.php" class="menu-item"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href="data_siswa.php" class="menu-item"><i class="fas fa-user-graduate"></i> <span>Data Siswa</span></a>
            <a href="data_guru.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span></a>
            <a href="kelola_kelas.php" class="menu-item active"><i class="fas fa-school"></i> <span>Kelola Kelas</span></a>
            <a href="kelola_berita.php" class="menu-item"><i class="fas fa-newspaper"></i> <span>Kelola Berita</span></a>
            <a href="kelola_ekstrakurikuler.php" class="menu-item"><i class="fas fa-futbol"></i> <span>Ekstrakurikuler</span></a>
            <a href="masukan.php" class="menu-item"><i class="fas fa-envelope-open-text"></i> <span>Masukan & Saran</span></a>
            <a href="kelola_galeri.php" class="menu-item"><i class="fas fa-images"></i> <span>Kelola Galeri</span></a>
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
            <h2>Kelola Data Kelas</h2>
            <p class="subtitle">Tambah, edit, atau hapus data kelas</p>
        </header>

        <div class="card" style="margin-bottom: 2rem;">
            <h3>Tambah Kelas Baru</h3>
            <?= $message ?>
            <form method="POST" style="margin-top: 1rem; display: flex; gap: 10px; align-items: flex-start;">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div style="flex: 1;">
                    <input type="text" name="nama_kelas" placeholder="Nama Kelas (Contoh: XII IPA 1)" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-top: 5px;">Format wajib: Tingkat Jurusan Nomor (ex: X IPA 1, XI IPS 2)</small>
                </div>
                <button type="submit" name="tambah_kelas" style="background: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; height: fit-content;">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Kelas</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">No</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">Nama Kelas</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center; width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($kelas_list->rowCount() > 0): ?>
                            <?php $no = 1;
                            while ($row = $kelas_list->fetch()): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px;"><?= $no++ ?></td>
                                    <td style="padding: 12px;">
                                        <form method="POST" style="display: flex; gap: 5px;">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="text" name="nama_kelas" value="<?= htmlspecialchars($row['nama_kelas']) ?>" required style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                            <button type="submit" name="edit_kelas" style="background: #4caf50; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">Simpan</button>
                                        </form>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kelas ini?')" style="color: #e53935; text-decoration: none;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px; color: #666;">Belum ada data kelas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>