<?php
// ekskul/ekstrakurikuler.php
include 'config/db.php';      // ✅ Naik ke folder utama
 include 'includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekstrakurikuler - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="style.css">
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
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ekskul-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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
    <!-- CONTENT -->
    <div class="content">
        <h1>Ekstrakurikuler</h1>

        <div class="layout">
            <!-- MAIN CONTENT (EKSKUL) -->
            <div class="main-content">
                <div class="ekskul-grid">
                    <!-- BARIS PERTAMA: 5 EKSKUL -->
                    <div class="ekskul-card">
                        <a href="ekskul-basket.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=Basket+Ball" alt="Basket Ball" class="ekskul-image">
                            <div class="ekskul-title">Basket Ball</div>
                        </a>
                    </div>

                    <div class="ekskul-card">
                        <a href="ekskul-bkc.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=BKC" alt="BKC" class="ekskul-image">
                            <div class="ekskul-title">BKC</div>
                        </a>
                    </div>

                    <div class="ekskul-card">
                        <a href="ekskul-computer-club.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=Computer+Fanc+Club" alt="Computer Fanc Club" class="ekskul-image">
                            <div class="ekskul-title">Computer Fanc Club</div>
                        </a>
                    </div>

                    <div class="ekskul-card">
                        <a href="ekskul-english-club.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=English+Club" alt="English Club" class="ekskul-image">
                            <div class="ekskul-title">English Club</div>
                        </a>
                    </div>

                    <div class="ekskul-card">
                        <a href="ekskul-irm.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=Ikatan+Remaja+Mesjid" alt="Ikatan Remaja Mesjid" class="ekskul-image">
                            <div class="ekskul-title">Ikatan Remaja Mesjid</div>
                        </a>
                    </div>

                    <!-- BARIS KEDUA: 3 EKSKUL -->
                    <div class="ekskul-card">
                        <a href="ekskul-pmr.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=Palang+Merah+Remaja" alt="Palang Merah Remaja" class="ekskul-image">
                            <div class="ekskul-title">Palang Merah Remaja</div>
                        </a>
                    </div>

                    <div class="ekskul-card">
                        <a href="ekskul-paskibra.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=Paskibra" alt="Paskibra" class="ekskul-image">
                            <div class="ekskul-title">Paskibra</div>
                        </a>
                    </div>

                    <div class="ekskul-card">
                        <a href="ekskul-renang.php" style="text-decoration: none; color: inherit;">
                            <img src="https://via.placeholder.com/300x200?text=Renang" alt="Renang" class="ekskul-image">
                            <div class="ekskul-title">Renang</div>
                        </a>
                    </div>
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