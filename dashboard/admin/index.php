<?php
// dashboard/admin/index.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['admin']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="navbar-logo">
            <h1>SMA BINA INSANI</h1>
        </div>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="berita.php">Kelola Berita</a>
            <a href="profil.php">Profil</a>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Halo, Admin <?= $_SESSION['nama'] ?>!</h2>
        <p class="subtitle">Panel Pengelolaan Website Sekolah</p>

        <div class="card">
            <h3>Fitur Admin</h3>
            <ul style="list-style: none; padding: 0; margin-top: 1rem;">
                <li style="margin: 0.8rem 0;">
                    <a href="berita.php" style="color: #2575fc; text-decoration: none; font-weight: bold;">➤ Tambah & Edit Berita Sekolah</a>
                </li>
                <li style="margin: 0.8rem 0; color: #666; font-style: italic;">
                    Fitur lain (kelola akun guru/siswa, tampilan website) bisa dikembangkan selanjutnya.
                </li>
            </ul>
        </div>

        <!-- Tampilkan 3 berita terbaru -->
        <div class="card">
            <h3>Berita Terbaru</h3>
            <?php
            $berita = $pdo->query("SELECT judul, tanggal FROM berita ORDER BY tanggal DESC LIMIT 3");
            if ($berita->rowCount() > 0):
                echo "<ul style='list-style: none; padding: 0;'>";
                while ($row = $berita->fetch()):
                    echo "<li style='margin: 0.6rem 0; padding-left: 1rem; border-left: 3px solid #2575fc;'>
                            <strong>{$row['judul']}</strong> 
                            <span style='color: #888; font-size: 0.9rem;'> – {$row['tanggal']}</span>
                          </li>";
                endwhile;
                echo "</ul>";
            else:
                echo "<p style='color: #888;'>Belum ada berita.</p>";
            endif;
            ?>
        </div>
    </div>
</body>
</html>