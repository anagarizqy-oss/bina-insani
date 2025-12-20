<?php
// agenda-kegiatan.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Agenda Kegiatan - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: #f9fbff;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #2575fc;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin: 1.5rem 0;
        }

        .event-list {
            list-style: none;
            padding: 0;
        }

        .event-item {
            display: flex;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .event-date {
            font-weight: bold;
            color: #2575fc;
            min-width: 100px;
        }

        .event-title {
            color: #333;
            flex: 1;
        }

        .event-location {
            color: #666;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Agenda Kegiatan</h1>
        <div class="section">
            <p>Berikut adalah agenda kegiatan sekolah bulan ini:</p>
            
            <ul class="event-list">
                <li class="event-item">
                    <div class="event-date">15 Jul 2025</div>
                    <div class="event-title">Upacara Pembukaan Tahun Ajaran Baru</div>
                    <div class="event-location">Lapangan Sekolah</div>
                </li>
                <li class="event-item">
                    <div class="event-date">20 Jul 2025</div>
                    <div class="event-title">Masa Pengenalan Lingkungan Sekolah (MPLS)</div>
                    <div class="event-location">Ruang Kelas & Lapangan</div>
                </li>
                <li class="event-item">
                    <div class="event-date">15 Agust 2025</div>
                    <div class="event-title">Peringatan HUT RI ke-80</div>
                    <div class="event-location">Lapangan Sekolah</div>
                </li>
                <li class="event-item">
                    <div class="event-date">10 Sep 2025</div>
                    <div class="event-title">Lomba Kemerdekaan</div>
                    <div class="event-location">Aula & Lapangan</div>
                </li>
                <li class="event-item">
                    <div class="event-date">25 Sep 2025</div>
                    <div class="event-title">Bazar Amal</div>
                    <div class="event-location">Halaman Depan Sekolah</div>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
