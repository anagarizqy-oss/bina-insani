<?php
// detail_berita.php
include 'config/db.php';

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch();

// Jika berita tidak ditemukan
if (!$berita) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($berita['judul']) ?> - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Reusing Navbar & Footer styles from index.php via assets/style.css if available, 
           checking index.php had inline styles for Header/Nav. I will copy crucial styles here for consistency. */

        /* === NAVBAR BARU === */
        .navbar-new {
            background: #2575fc;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-left {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .nav-left a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 0.4rem 0.6rem;
        }

        .nav-left a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s;
        }

        .nav-left a:hover::after {
            width: 100%;
        }

        .nav-right {
            display: flex;
            gap: 1rem;
        }

        .btn-ppdb,
        .btn-login-nav {
            padding: 0.5rem 1.2rem;
            border-radius: 30px;
            font-weight: bold;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.3s;
        }

        .btn-ppdb {
            background: #1a68e8;
            color: white;
        }

        .btn-ppdb:hover {
            background: #1552b8;
        }

        .btn-login-nav {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-login-nav:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* DETAIL CONTENT */
        .news-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .news-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .news-title {
            font-size: 2.5rem;
            color: #2575fc;
            margin-bottom: 0.5rem;
        }

        .news-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .news-cover-full {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .news-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .news-body img {
            max-width: 100%;
            height: auto;
        }

        .back-btn {
            display: inline-block;
            margin-top: 2rem;
            color: #2575fc;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
        }

        /* DROPDOWN */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            top: 100%;
            left: 0;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            border-radius: 8px;
        }

        .show {
            display: block;
        }

        @media (max-width: 768px) {
            .news-title {
                font-size: 1.8rem;
            }

            .news-container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body style="background: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0;">

    <!-- NAVBAR BARU -->
    <nav class="navbar-new">
        <div class="nav-left">
            <a href="index.php">Beranda</a>

            <!-- PROFIL KAMI -->
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('profil-kami')">
                    Profil Kami ▾
                </a>
                <div id="profil-kami" class="dropdown-content">
                    <a href="profil-sekolah.php">Profil Sekolah</a>
                    <a href="identitas.php">Identitas Sekolah</a>
                    <a href="visimisi.php">Visi & Misi</a>
                    <a href="sejarah.php">Sejarah Singkat</a>
                    <a href="struktur.php">Struktur Organisasi</a>
                    <a href="fasilitas.php">Fasilitas</a>
                    <a href="staf-pengajar.php">Staf Pengajar</a>
                    <a href="tenaga-kependidikan.php">Staf Tenaga Kependidikan</a>
                </div>
            </div>
            <!-- AGENDA -->
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('agenda')">
                    Agenda ▾
                </a>
                <div id="agenda" class="dropdown-content">
                    <a href="agenda-kegiatan.php">Agenda Kegiatan</a>
                    <a href="kalender-akademik.php">Kalender Akademik</a>
                    <a href="jadwal-uji.php">Jadwal Ujian</a>
                    <a href="libur-nasional.php">Libur Nasional</a>
                </div>
            </div>
            <a href="ekstrakurikuler.php">Ekstrakurikuler</a>
            <a href="info.php">Informasi</a>
            <a href="index.php#galeri">Galeri</a>
            <a href="masukan.php">Masukan & Saran</a>
            <a href="kontak.php">Kontak</a>
        </div>
        <div class="nav-right">
            <a href="login.php" class="btn-login-nav">Login</a>
            <a href="#" class="btn-ppdb">PPDB</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="news-container">
        <div class="news-header">
            <h1 class="news-title"><?= htmlspecialchars($berita['judul']) ?></h1>
            <div class="news-meta">
                <i class="far fa-calendar-alt"></i> <?= date('d F Y', strtotime($berita['tanggal'])) ?>
            </div>
        </div>

        <?php if (!empty($berita['cover'])): ?>
            <img src="<?= htmlspecialchars($berita['cover']) ?>" alt="<?= htmlspecialchars($berita['judul']) ?>" class="news-cover-full">
        <?php endif; ?>

        <div class="news-body">
            <!-- Output Raw HTML from TinyMCE -->
            <?= $berita['isi'] ?>
        </div>

        <a href="index.php" class="back-btn">← Kembali ke Beranda</a>
    </div>

    <!-- FOOTER -->
    <footer>
        &copy; <?= date('Y') ?> SMA Bina Insani Wonogiri. All Rights Reserved.<br>
        Jl. Raya Wonogiri, Jawa Tengah<br>
        <a href="kontak.php" style="color: #2575fc; text-decoration: none; margin-top: 10px; display: inline-block;">Lihat Lokasi & Kontak</a>
    </footer>

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            document.querySelectorAll('.dropdown-content').forEach(el => {
                if (el.id !== id) el.classList.remove('show');
            });
            dropdown.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>