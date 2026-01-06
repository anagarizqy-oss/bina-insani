<?php
// identitas.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Sekolah - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bowlby+One&family=Karla:ital,wght@0,200..800;1,200..800&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dinamis.css?v=<?= time() ?>">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>
    <!-- PAGE HERO -->
    <section class="page-hero">
        <div class="hero-content-sub">
            <h1>IDENTITAS SEKOLAH</h1>
            <p>Data Resmi dan Legalitas Sekolah</p>
        </div>
    </section>

    <!-- CONTENT -->
    <div class="profile-container">
        <div class="section" style="background: transparent; box-shadow: none; padding: 0;">
            <div class="table-container">
                <table class="modern-table">
                    <tbody>
                        <tr>
                            <td class="table-label">Nama Sekolah</td>
                            <td class="table-value">SMA Bina Insani Wonogiri</td>
                        </tr>
                        <tr>
                            <td class="table-label">NPSN</td>
                            <td class="table-value">20321007</td>
                        </tr>
                        <tr>
                            <td class="table-label">Alamat</td>
                            <td class="table-value">Jl. Raya Wonogiri, Desa Boto, Kec. Wonogiri, Kab. Wonogiri, Jawa Tengah</td>
                        </tr>
                        <tr>
                            <td class="table-label">Akreditasi</td>
                            <td class="table-value">A (Unggul)</td>
                        </tr>
                        <tr>
                            <td class="table-label">Kurikulum</td>
                            <td class="table-value">Kurikulum Merdeka</td>
                        </tr>
                        <tr>
                            <td class="table-label">Tahun Berdiri</td>
                            <td class="table-value">2005</td>
                        </tr>
                        <tr>
                            <td class="table-label">Email</td>
                            <td class="table-value">admin@smabinainsani.sch.id</td>
                        </tr>
                        <tr>
                            <td class="table-label">Telepon</td>
                            <td class="table-value">(0273) 321678</td>
                        </tr>
                        <tr>
                            <td class="table-label">Kepala Sekolah</td>
                            <td class="table-value">Drs. H. Sumarno, M.Pd</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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


</body>

</html>