<?php
// sejarah.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sejarah Singkat - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/style.css">
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

        p {
            line-height: 1.7;
            margin: 1rem 0;
            font-size: 1.05rem;
        }

        .timeline {
            margin-top: 1.5rem;
            padding-left: 1.5rem;
            border-left: 3px solid #2575fc;
        }

        .timeline-item {
            margin-bottom: 1.2rem;
        }

        .timeline-year {
            font-weight: bold;
            color: #2575fc;
        }
    </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>


    <div class="content">
        <h1>Sejarah Singkat</h1>
        <div class="section">
            <p>SMA Bina Insani Wonogiri didirikan pada tahun 2005 oleh Yayasan Pendidikan Bina Insani dengan tujuan menyediakan pendidikan berkualitas bagi masyarakat Wonogiri.</p>
            <p>Sejak berdiri, sekolah terus berkembang dan telah meluluskan ribuan siswa yang sukses di perguruan tinggi dan dunia kerja.</p>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">2005</div>
                    <p>Didirikan di atas tanah seluas 5.000 m² dengan 4 ruang kelas dan 20 siswa angkatan pertama.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2010</div>
                    <p>Memperoleh akreditasi B, menambah fasilitas laboratorium IPA dan komputer.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2018</div>
                    <p>Naik akreditasi menjadi A (Unggul) dan menerapkan kurikulum berbasis karakter.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2023</div>
                    <p>Mengadopsi Kurikulum Merdeka dan membangun gedung baru ber-AC untuk kelas XII.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>