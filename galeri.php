<?php
// galeri.php
include 'config/db.php';
include 'includes/auth.php'; // Optional, but good for consistent session start if needed

// Ambil data galeri
$query = "SELECT * FROM galeri ORDER BY tanggal_foto DESC, created_at DESC";
$stmt = $pdo->query($query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan - SMA BINA INSANI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Container Layout Masonry */
        .gallery-container {
            column-count: 4;
            column-gap: 15px;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive Columns */
        @media (max-width: 1024px) {
            .gallery-container {
                column-count: 3;
            }
        }

        @media (max-width: 768px) {
            .gallery-container {
                column-count: 2;
            }
        }

        @media (max-width: 480px) {
            .gallery-container {
                column-count: 1;
            }
        }

        /* Gallery Item */
        .gallery-item {
            break-inside: avoid;
            margin-bottom: 15px;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        /* Overlay Style */
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0) 100%);
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
        }

        /* Show overlay on hover or focus (touch) */
        .gallery-item:hover .gallery-overlay,
        .gallery-item:focus-within .gallery-overlay,
        .gallery-item:active .gallery-overlay {
            opacity: 1;
        }

        .overlay-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: capitalize;
        }

        .overlay-content p {
            font-size: 0.9rem;
            margin: 0;
            color: #ddd;
            line-height: 1.4;
        }

        .overlay-date {
            font-size: 0.8rem;
            color: #ffca28;
            margin-bottom: 0.3rem;
            display: inline-block;
            font-weight: 500;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 3rem 1rem;
            background: #f8f9fa;
            margin-bottom: 1rem;
        }

        .page-header h1 {
            color: #2575fc;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #666;
        }
    </style>
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="page-header">
        <h1>Galeri Kegiatan Sekolah</h1>
        <p>Dokumentasi aktivitas dan kegiatan siswa-siswi SMA Bina Insani</p>
    </div>

    <div class="gallery-container">
        <?php if ($stmt->rowCount() > 0): ?>
            <?php while ($row = $stmt->fetch()): ?>
                <div class="gallery-item" tabindex="0">
                    <img src="<?= htmlspecialchars($row['url_foto']) ?>" alt="Kegiatan Sekolah" loading="lazy">
                    <div class="gallery-overlay">
                        <div class="overlay-content">
                            <span class="overlay-date">
                                <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['tanggal_foto'])) ?>
                            </span>
                            <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; font-style: italic; color: #888; grid-column: 1 / -1;">Belum ada foto galeri.</p>
        <?php endif; ?>
    </div>

    <!-- Reusing Footer Style from Index -->
    <footer style="text-align: center; padding: 2rem; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; margin-top: 3rem;">
        &copy; <?= date('Y') ?> SMA Bina Insani Wonogiri. All Rights Reserved.<br>
        Jl. Raya Wonogiri, Jawa Tengah<br>
        <a href="kontak.php" style="color: #2575fc; text-decoration: none; margin-top: 10px; display: inline-block;">Lihat Lokasi & Kontak</a>
    </footer>

</body>

</html>