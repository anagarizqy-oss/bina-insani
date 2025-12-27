<?php
include '../config/db.php';

$nama = $_GET['nama'] ?? '';
$ekskul = null;

if ($nama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM ekstrakurikuler WHERE nama_ekskul = ?");
    $stmt->execute([$nama]);
    $ekskul = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$ekskul) {
    die("Data ekstrakurikuler tidak ditemukan.");
}

// Generate deterministic color
$bg_color = substr(md5($ekskul['nama_ekskul']), 0, 6);

// Absolute path helper
$basePath = dirname(__DIR__);
$coverPath = !empty($ekskul['cover_image']) && file_exists($basePath . '/' . $ekskul['cover_image'])
    ? '../' . $ekskul['cover_image']
    : null;

$bgImagePath = !empty($ekskul['background_image']) && file_exists($basePath . '/' . $ekskul['background_image'])
    ? '../' . $ekskul['background_image']
    : null;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ekskul['nama_ekskul']) ?> - Ekstrakurikuler</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ekskul-bg: #<?= $bg_color ?>;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .header-banner {
            background:
                linear-gradient(rgba(0, 0, 0, .6), rgba(0, 0, 0, .6)) <?= $bgImagePath ? ", url('$bgImagePath')" : ", linear-gradient(135deg, var(--ekskul-bg), #000)" ?>;
            background-size: cover;
            background-position: center;
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .ekskul-cover {
            width: 100%;
            height: 300px;
            background-color: var(--ekskul-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 5rem;
            font-weight: bold;
            border-radius: 12px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .ekskul-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

</head>

<body>
    <nav class="navbar-new">
        <div class="nav-left">
            <a href="../index.php">Beranda</a>

            <!-- PROFIL KAMI -->
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('profil-kami')">
                    Profil Kami
                </a>
                <div id="profil-kami" class="dropdown-content">
                    <a href="../profil-sekolah.php">Profil Sekolah</a>
                    <a href="../identitas.php">Identitas Sekolah</a>
                    <a href="../visimisi.php">Visi & Misi</a>
                    <a href="../sejarah.php">Sejarah Singkat</a>
                    <a href="../struktur.php">Struktur Organisasi</a>
                    <a href="../fasilitas.php">Fasilitas</a>
                    <a href="../staf-pengajar.php">Staf Pengajar</a>
                    <a href="../tenaga-kependidikan.php">Staf Tenaga Kependidikan</a>
                </div>
            </div>
            <!-- AGENDA -->
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('agenda')">
                    Agenda
                </a>
                <div id="agenda" class="dropdown-content">
                    <a href="../agenda-kegiatan.php">Agenda Kegiatan</a>
                    <a href="../kalender-akademik.php">Kalender Akademik</a>
                    <a href="../jadwal-uji.php">Jadwal Ujian</a>
                    <a href="../libur-nasional.php">Libur Nasional</a>
                </div>
            </div>
            <a href="../ekstrakurikuler.php">Ekstrakurikuler</a>
            <a href="../info.php">Informasi</a>
            <a href="#galeri">Galeri</a>
            <a href="#masukan">Masukan & Saran</a>
            <a href="../kontak.php">Kontak</a>
        </div>
        <div class="nav-right">
            <a href="login.php" class="btn-login-nav">Login</a>
            <a href="dashboard/ppdb/ppdb.php" class="btn-ppdb">PPDB</a>
        </div>
    </nav>
    <div class="header-banner">
        <h1><?= htmlspecialchars($ekskul['nama_ekskul']) ?></h1>
    </div>

    <div class="content-container">

        <div class="card">
            <!-- Cover Image -->
            <div class="ekskul-cover">
                <?php if (!empty($ekskul['cover_image']) && file_exists('../' . $ekskul['cover_image'])): ?>
                    <img src="../<?= htmlspecialchars($ekskul['cover_image']) ?>" alt="<?= htmlspecialchars($ekskul['nama_ekskul']) ?>">
                <?php else: ?>
                    <?= strtoupper(substr($ekskul['nama_ekskul'], 0, 1)) ?>
                <?php endif; ?>
            </div>

            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-chalkboard-teacher"></i> Guru Pembimbing</div>
                <div class="detail-value"><?= htmlspecialchars($ekskul['guru_pembimbing']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-user-graduate"></i> Ketua Ekskul</div>
                <div class="detail-value"><?= htmlspecialchars($ekskul['ketua']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label"><i class="fas fa-user"></i> Wakil Ketua</div>
                <div class="detail-value"><?= !empty($ekskul['wakil_ketua']) ? htmlspecialchars($ekskul['wakil_ketua']) : '-' ?></div>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <div class="detail-label"><i class="fas fa-users"></i> Jumlah Anggota</div>
                <div class="detail-value"><?= htmlspecialchars($ekskul['jumlah_anggota']) ?> Siswa</div>
            </div>

            <a href="../ekstrakurikuler.php" class="back-btn">&larr; Kembali ke Daftar Ekskul</a>
        </div>

    </div>

    <!-- FOOTER -->
    <footer style="text-align: center; padding: 2rem; color: #666; margin-top: auto;">
        &copy; <?= date('Y') ?> SMA Bina Insani Wonogiri. All Rights Reserved.
    </footer>

</body>

</html>