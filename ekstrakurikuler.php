<?php
// ekskul/ekstrakurikuler.php
include 'config/db.php';      // âœ… Naik ke folder utama
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekstrakurikuler - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* === BODY DENGAN GRADIENT SAMA SEPERTI INDEX.PHP === */
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .content {
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* === LAYOUT 2 KOLOM === */
        .layout {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .main-content {
            flex: 1;
            min-width: 300px;
        }

        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            max-width: 350px;
            width: 100%;
        }

        .sidebar h2 {
            color: #2575fc;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .article-list {
            list-style: none;
            padding: 0;
        }

        .article-item {
            padding: 0.8rem 0;
            border-bottom: 1px solid #eee;
        }

        .article-title {
            color: #2575fc;
            font-weight: bold;
            margin: 0.5rem 0;
            line-height: 1.4;
        }

        .article-date {
            color: #666;
            font-size: 0.9rem;
            margin: 0.3rem 0;
        }

        /* === EKSKUL GRID === */
        .ekskul-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .ekskul-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ekskul-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .ekskul-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .ekskul-title {
            padding: 1rem;
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        /* RESPONSIF */
        @media (max-width: 768px) {
            .content {
                padding: 2rem 1rem;
            }

            .layout {
                flex-direction: column;
                gap: 1.5rem;
            }

            .main-content {
                min-width: 100%;
            }

            .sidebar {
                max-width: 100%;
            }

            .ekskul-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->

    <?php include 'includes/navbar.php'; ?> <!-- âœ… Sudah benar -->

    <!-- CONTENT -->
    <div class="content">
        <h1>Ekstrakurikuler</h1>

        <div class="layout">
            <!-- MAIN CONTENT (EKSKUL) -->
            <div class="main-content">
                <div class="ekskul-grid">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY nama_ekskul ASC");
                        while ($row = $stmt->fetch()) {
                            // Generate random color for placeholder if no image (assuming no image upload exists yet)
                            $bg_color = substr(md5($row['nama_ekskul']), 0, 6);
                    ?>
                            <div class="ekskul-card">
                                <a href="ekskul/detail-ekskul.php?nama=<?= urlencode($row['nama_ekskul']) ?>" style="text-decoration: none; color: inherit;">
                                    <?php if (!empty($row['cover_image']) && file_exists($row['cover_image'])): ?>
                                        <img src="<?= htmlspecialchars($row['cover_image']) ?>" alt="<?= htmlspecialchars($row['nama_ekskul']) ?>" class="ekskul-image">
                                    <?php else: ?>
                                        <!-- Placeholder Image with Name -->
                                        <div style="width: 100%; height: 200px; background-color: #<?= $bg_color ?>; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                            <?= strtoupper(substr($row['nama_ekskul'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="ekskul-title">
                                        <?= htmlspecialchars($row['nama_ekskul']) ?>
                                        <div style="font-size: 0.85rem; font-weight: normal; color: #666; margin-top: 8px; text-align: left; padding: 0 10px;">
                                            <div style="margin-bottom: 4px;"><i class="fas fa-user-tie"></i> Pembimbing: <?= htmlspecialchars($row['guru_pembimbing']) ?></div>
                                            <div><i class="fas fa-users"></i> Anggota: <?= htmlspecialchars($row['jumlah_anggota']) ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                    <?php
                        }
                    } catch (PDOException $e) {
                        echo "<p>Gagal memuat data ekstrakurikuler.</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- SIDEBAR ARTIKEL TERBARU -->
            <div class="sidebar">
                <h2>Artikel Terbaru</h2>
                <ul class="article-list">
                    <li class="article-item">
                        <div class="article-title">LAPORAN REKAPITULASI REALISASI PENGUNAAN DANA BOS REGULER TAHAP I TAHUN 2025</div>
                        <div class="article-date">Juli 23, 2025</div>
                    </li>
                    <li class="article-item">
                        <div class="article-title">Laporan Penggunaan Dana BOSP 2022 (Pengadaan Buku Tahun 2023)</div>
                        <div class="article-date">Mei 5, 2025</div>
                    </li>
                    <li class="article-item">
                        <div class="article-title">Mendalami Manfaat Pentingnya Website Sekolah</div>
                        <div class="article-date">Juni 8, 2024</div>
                    </li>
                    <li class="article-item">
                        <div class="article-title">Mengasah Bakat Seni: Workshop Lukisan dan Seni Kerajinan untuk Siswa Sekolah</div>
                        <div class="article-date">Juni 4, 2024</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>