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

    <style>
        /* RESPONSIF */
        .navbar-new {
            background: transparent;
            position: absolute;
            display: flex;

            width: 100%;
            justify-content: center;
            align-items: center;
            top: 0;
            left: 0;
            right: 0;
            box-shadow: none;
        }

        .navbar-new .nav-right {
            display: none;
        }

        .hero {
            background: url('assets/bangunan.jpeg');
            width: 100%;
            height: 100vh;
            background-size: cover;
            background-position: center;
            padding: 0;
            margin: 0;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;

            background: linear-gradient(to top right,
                    rgba(0, 0, 0, 0.75) 0%,
                    /* hitam pekat mulai */
                    rgba(0, 0, 0, 0.5) 40%,
                    /* hitam pekat berhenti di 20% */
                    rgba(0, 0, 0, 0.2) 100%
                    /* transparan di kanan atas */
                );
            height: 100vh;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            text-align: center;
        }

        .hero-content h1,
        span {
            font-family: 'Anton', cursive;
            font-size: 4rem;
            text-align: left;

        }

        .hero-content h1 span {
            font-family: 'Anton', cursive;
            font-size: 4rem;
            text-align: center;
        }

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
                text-shadow: 0 0 6px rgba(0, 0, 0, 0.8);
            }

            .hero p {
                font-size: 1rem;
                text-shadow: 0 0 4px rgba(0, 0, 0, 0.7);
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
    <div class="section">
        <h2>Berita Terbaru</h2>
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

    <!-- MASUKAN & SARAN -->
    <div id="masukan" class="section">
        <h2>Masukan & Saran</h2>
        <p>Kami terbuka terhadap masukan dan saran dari orang tua, siswa, dan masyarakat.</p>

        <?php if ($error): ?>
            <div class="alert error" style="padding: 10px; margin: 15px 0; border-radius: 6px; background: #ffebee; color: #c62828; border: 1px solid #ef9a9a;">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success" style="padding: 10px; margin: 15px 0; border-radius: 6px; background: #e8f5e9; color: #2e7d32;">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div style="margin-bottom: 1.2rem;">
                    <label for="nama" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" placeholder="Contoh: Andi Prasetyo" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Email (Opsional)</label>
                    <input type="email" name="email" id="email" placeholder="Contoh: andi@email.com" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <label for="subjek" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Subjek (Opsional)</label>
                    <input type="text" name="subjek" id="subjek" placeholder="Contoh: Saran Penyempurnaan Website" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <label for="pesan" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Pesan *</label>
                    <textarea name="pesan" id="pesan" rows="5" placeholder="Tuliskan masukan atau saran Anda..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"></textarea>
                </div>

                <button type="submit" name="submit_masukan" style="background: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s;">Kirim Masukan</button>
            </form>
        </div>
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