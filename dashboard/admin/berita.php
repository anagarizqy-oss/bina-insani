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
    header("Location: berita.php");
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
    <title>Kelola Berita - Admin</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <h1>SMA BINA INSANI</h1>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="berita.php" style="border-bottom: 2px solid white;">Berita</a>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Kelola Berita Sekolah</h2>

        <!-- Form Tambah Berita -->
        <div class="card">
            <h3>Tambah Berita Baru</h3>
            <?= $message ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <label>Judul Berita</label>
                <input type="text" name="judul" placeholder="Contoh: Upacara Hari Pahlawan" required>

                <label>Isi Berita</label>
                <textarea name="isi" rows="5" placeholder="Tulis isi berita di sini..." required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;"></textarea>

                <button type="submit" name="tambah_berita">Publikasikan Berita</button>
            </form>
        </div>

        <!-- Daftar Berita -->
        <div class="card">
            <h3>Daftar Berita</h3>
            <?php if ($berita_list->rowCount() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $berita_list->fetch()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td>
                                <a href="?hapus=<?= $row['id'] ?>" 
                                   onclick="return confirm('Hapus berita ini?')" 
                                   style="color: #e53935; text-decoration: none;">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #888;">Belum ada berita.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>