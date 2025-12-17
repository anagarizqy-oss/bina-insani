<?php
// index.php - Halaman publik (tanpa login)
include 'config/db.php';

// Ambil 3 berita terbaru
$berita = $pdo->query("SELECT judul, isi, tanggal FROM berita ORDER BY tanggal DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA BINA INSANI WONOGIRI - Website Resmi</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
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

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            text-align: center;
            padding: 5rem 2rem 4rem;
        }

        .hero img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 0;
            border: none;
            box-shadow: none;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInBounce 1s ease-out forwards;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInBounce 1s ease-out 0.3s forwards;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 1.5rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInBounce 1s ease-out 0.6s forwards;
        }

        .btn-login-hero {
            display: inline-block;
            background: white;
            color: #2575fc;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInBounce 1s ease-out 0.9s forwards;
        }

        .btn-login-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        /* BERITA & FOOTER */
        .section {
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .section h2 {
            text-align: center;
            color: #2575fc;
            margin-bottom: 2rem;
        }

        .news-item {
            background: white;
            padding: 1.5rem;
            margin: 1.2rem 0;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .news-date {
            color: #2575fc;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
        }

        /* ANIMASI */
        @keyframes fadeInBounce {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            60% {
                opacity: 1;
                transform: translateY(-5px);
            }

            80% {
                transform: translateY(3px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIF */
        @media (max-width: 768px) {
            .nav-left {
                gap: 0.8rem;
            }

            .nav-left a {
                font-size: 0.9rem;
                padding: 0.3rem 0.5rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero {
                padding: 4rem 1.5rem 3rem;
            }
        }

        /* SCROLL SMOOTH */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>
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
            <a href="ekskul/ekstrakulikuler.php">Ekstrakurikuler</a>
            <a href="#info">Informasi</a>
            <a href="#galeri">Galeri</a>
            <a href="#masukan">Masukan & Saran</a>
            <a href="kontak.php">Kontak</a>
        </div>
        <div class="nav-right">
            <a href="login.php" class="btn-login-nav">Login Akun</a>
            <a href="#" class="btn-ppdb">PPDB</a>
        </div>
    </nav>
    <!-- HERO SECTION -->
    <div class="hero">
        <img src="assets/sekolah.png" alt="SMA Bina Insani Wonogiri">
        <h1>SMA BINA INSANI WONOGIRI</h1>
        <p>Mewujudkan Generasi Unggul, Berakhlak, dan Berprestasi</p>
        <a href="login.php" class="btn-login-hero">Login Akun</a>
    </div>

    <!-- BERITA -->
    <div class="section">
        <h2>Berita Terbaru</h2>
        <?php if ($berita->rowCount() > 0): ?>
            <?php while ($row = $berita->fetch()): ?>
                <div class="news-item">
                    <div class="news-date"><?= htmlspecialchars($row['tanggal']) ?></div>
                    <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    <p><?= nl2br(htmlspecialchars(substr($row['isi'], 0, 200))) ?>...</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: #888;">Belum ada berita.</p>
        <?php endif; ?>
    </div>



    <div id="masukan" class="section">
        <h2>Masukan & Saran</h2>
        <p>Kami terbuka terhadap masukan dan saran dari orang tua, siswa, dan masyarakat. Silakan kirim melalui formulir di halaman kontak.</p>
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

            // Tutup semua dropdown lain yang sedang terbuka
            document.querySelectorAll('.dropdown-content').forEach(el => {
                if (el.id !== id) {
                    el.classList.remove('show');
                }
            });

            // Toggle class 'show' pada dropdown yang diklik
            dropdown.classList.toggle('show');
        }

        // Tutup dropdown saat klik di luar area menu
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