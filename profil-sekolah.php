<?php
// profil-sekolah.php
include 'config/db.php';
$jumlah_guru = $pdo->query("SELECT * FROM guru")->rowCount();
$jumlah_siswa = $pdo->query("SELECT * FROM siswa")->rowCount();
$jumlah_ekstrakurikuler = $pdo->query("SELECT * FROM ekstrakurikuler")->rowCount();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sekolah - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bowlby+One&family=Karla:ital,wght@0,200..800;1,200..800&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dinamis.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- PAGE HERO -->
    <section class="page-hero">
        <div class="hero-content-sub">
            <h1>PROFIL SEKOLAH</h1>
            <p>Mengenal lebih dekat SMA Bina Insani Wonogiri</p>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="profile-container">
        <!-- Intro Section -->
        <section class="profile-section profile-intro">
            <h2>Visi & Misi</h2>
            <p>
                "Mewujudkan generasi unggul, berakhlak mulia, berwawasan global, serta mampu bersaing di era digital dengan tetap menjunjung tinggi nilai-nilai kearifan lokal."
            </p>
        </section>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?php echo $jumlah_guru; ?></span>
                <span class="stat-label">Guru & Staf</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $jumlah_siswa; ?></span>
                <span class="stat-label">Siswa Aktif</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $jumlah_ekstrakurikuler; ?></span>
                <span class="stat-label">Ekstrakurikuler</span>
            </div>

        </div>



        <section class="profile-section history-section" style="border-left-color: var(--tertiary-color);">
            <h3>Kenapa Memilih Kami?</h3>
            <ul style="list-style: none; padding: 0; margin-top: 1rem;">
                <li style="margin-bottom: 0.5rem; display: flex; gap: 10px; align-items: center;">
                    <span style="color: var(--tertiary-color); font-weight: bold;">✓</span> Kurikulum Merdeka yang adaptif
                </li>
                <li style="margin-bottom: 0.5rem; display: flex; gap: 10px; align-items: center;">
                    <span style="color: var(--tertiary-color); font-weight: bold;">✓</span> Fasilitas laboratorium lengkap
                </li>
                <li style="margin-bottom: 0.5rem; display: flex; gap: 10px; align-items: center;">
                    <span style="color: var(--tertiary-color); font-weight: bold;">✓</span> Lingkungan belajar yang asri dan kondusif
                </li>
                <li style="margin-bottom: 0.5rem; display: flex; gap: 10px; align-items: center;">
                    <span style="color: var(--tertiary-color); font-weight: bold;">✓</span> Program beasiswa prestasi dan kurang mampu
                </li>
            </ul>
        </section>
    </div>

    <!-- FOOTER MODERN (Copied from index.php) -->
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Kolom 1: Profil Sekolah -->
                <div class="footer-col">
                    <div class="footer-brand">
                        <img src="assets/sekolah.png" alt="Logo SMA Bina Insani" class="footer-logo">
                        <h3>SMA BINA INSANI<br>WONOGIRI</h3>
                    </div>
                    <p class="footer-desc">
                        Mewujudkan generasi unggul, berakhlak mulia, dan berwawasan global.
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook">FB</a>
                        <a href="#" aria-label="Instagram">IG</a>
                        <a href="#" aria-label="YouTube">YT</a>
                    </div>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div class="footer-col">
                    <h4>Tautan Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="profil-sekolah.php">Profil Sekolah</a></li>
                        <li><a href="agenda-kegiatan.php">Agenda Kegiatan</a></li>
                        <li><a href="ekstrakurikuler.php">Ekstrakurikuler</a></li>
                        <li><a href="kontak.php">Hubungi Kami</a></li>
                        <li><a href="login.php">Login Admin</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak -->
                <div class="footer-col">
                    <h4>Hubungi Kami</h4>
                    <ul class="footer-contact">
                        <li>
                            <span>📍</span>
                            <span>Jl. Raya Wonogiri - Ponorogo KM. 5, Brubuh, Wonogiri, Jawa Tengah</span>
                        </li>
                        <li>
                            <span>📞</span>
                            <span>(0273) 321678</span>
                        </li>
                        <li>
                            <span>📧</span>
                            <span>admin@smabinainsani.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> SMA Bina Insani Wonogiri. All Rights Reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>


    <!-- Script tambahan untuk navbar style pada halaman statis -->

</body>

</html>