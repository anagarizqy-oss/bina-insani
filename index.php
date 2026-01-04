<?php
// index.php - Halaman publik (tanpa login)
include 'config/db.php';
include 'includes/csrf.php';

// Ambil 3 berita terbaru
$berita = $pdo->query("SELECT id, judul, isi, tanggal, cover FROM berita ORDER BY tanggal DESC LIMIT 3");

// Inisialisasi error & success
$error = '';
$success = '';

// Proses form masukan & saran
if ($_POST && isset($_POST['submit_masukan'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $error = "Permintaan tidak valid.";
    } else {
        $nama = clean($_POST['nama']);
        $email = clean($_POST['email']);
        $subjek = clean($_POST['subjek']);
        $pesan = $_POST['pesan'];

        if (empty($nama) || empty($pesan)) {
            $error = "Nama dan Pesan wajib diisi.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO masukan_saran (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama, $email, $subjek, $pesan]);
                $success = "Terima kasih atas masukan Anda! Kami akan mempertimbangkan saran Anda.";
                // Reset form
                $_POST = [];
            } catch (Exception $e) {
                $error = "Gagal menyimpan masukan. Silakan coba lagi.";
            }
        }
    }
}

$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA BINA INSANI WONOGIRI - Website Resmi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bowlby+One&family=Karla:ital,wght@0,200..800;1,200..800&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Oswald:wght@200..700&display=swap" rel="stylesheet">


</head>

<body>
    <!-- NAVBAR BARU -->
    <nav class="navbar-new">
        <div class="hamburger" id="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="nav-left" id="nav-menu">
            <a href="index.php">Beranda</a>

            <!-- PROFIL KAMI -->
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('profil-kami')">
                    Profil Kami
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
                    Agenda
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
            <a href="galeri.php">Galeri</a>
            <a href="#masukan">Masukan & Saran</a>
            <a href="kontak.php">Kontak</a>
        </div>
        <div class="nav-right">
            <a href="login.php" class="btn-login-nav">Login</a>
            <a href="dashboard/ppdb/ppdb.php" class="btn-ppdb">PPDB</a>
        </div>
    </nav>
    <!-- HERO SECTION -->
    <div class="hero">
        <div class="hero-overlay"></div>

        <div class="hero-parallelogram">
            <div class="hero-content">
                <img src="assets/sekolah.png" alt="SMA Bina Insani Wonogiri">
                <span>SMA</span>
                <h1>BINA INSANI WONOGIRI</h1>
                <p>Mewujudkan Generasi Unggul, Berakhlak, dan Berprestasi</p>
                <a href="login.php" class="btn-login-hero">Login Akun</a>
            </div>
        </div>
    </div>

    <!-- BERITA -->
    <div class="section news">
        <h2>Berita Terbaru</h2>
        <div class="news-container">
            <?php if ($berita->rowCount() > 0): ?>
                <?php while ($row = $berita->fetch()): ?>
                    <div class="news-item">
                        <?php if (!empty($row['cover'])): ?>
                            <a href="detail_berita.php?id=<?= $row['id'] ?>">
                                <img src="<?= htmlspecialchars($row['cover']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" class="news-cover">
                            </a>
                        <?php endif; ?>
                        <div class="news-date"><?= htmlspecialchars($row['tanggal']) ?></div>
                        <h3>
                            <a href="detail_berita.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
                                <?= htmlspecialchars($row['judul']) ?>
                            </a>
                        </h3>
                        <p><?= htmlspecialchars(substr(strip_tags($row['isi']), 0, 200)) ?>...</p>
                        <a href="detail_berita.php?id=<?= $row['id'] ?>" style="display: inline-block; margin-top: 10px; color: #2575fc; font-weight: bold; text-decoration: none;">Baca Selengkapnya </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #888;">Belum ada berita.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- MASUKAN & SARAN -->
    <!-- MASUKAN & SARAN MODERN -->
    <div id="masukan" class="section feedback-section">
        <div class="feedback-container">
            <div class="feedback-grid">
                <!-- Kolom Kiri: Info -->
                <div class="feedback-info">
                    <h2>Kami Ingin Mendengar Anda</h2>
                    <p>Masukan Anda sangat berarti bagi pengembangan sekolah kami. Jangan ragu untuk berbagi saran, kritik, atau apresiasi.</p>
                    <div class="contact-highlight">
                        <span>📧 smabinainsaniwonogiri@gmail.com</span>
                        <span>📞 (0273) 123456</span>
                    </div>
                </div>

                <!-- Kolom Kanan: Form -->
                <div class="feedback-card">
                    <h3>Kirim Masukan</h3>

                    <?php if ($error): ?>
                        <div class="alert error"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-input" placeholder="Nama Anda" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email (Opsional)</label>
                            <input type="email" name="email" id="email" class="form-input" placeholder="contoh@email.com">
                        </div>

                        <div class="form-group">
                            <label for="subjek">Subjek</label>
                            <input type="text" name="subjek" id="subjek" class="form-input" placeholder="Topik masukan">
                        </div>

                        <div class="form-group">
                            <label for="pesan">Pesan</label>
                            <textarea name="pesan" id="pesan" rows="4" class="form-input" placeholder="Tulis masukan Anda di sini..." required></textarea>
                        </div>

                        <button type="submit" name="submit_masukan" class="btn-submit">Kirim Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        &copy; <?= date('Y') ?> SMA Bina Insani Wonogiri. All Rights Reserved.<br>
        Jl. Raya Wonogiri, Jawa Tengah<br>
        <a href="kontak.php" style="color: #2575fc; text-decoration: none; margin-top: 10px; display: inline-block;">Lihat Lokasi & Kontak</a>
    </footer>
    <script src="assets/js/index.js"></script>
</body>

</html>