<?php
// visimisi.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Visi & Misi - SMA BINA INSANI WONOGIRI</title>
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

        p {
            line-height: 1.6;
            margin: 1rem 0;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin: 1.5rem 0;
        }

        h2 {
            color: #2575fc;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'includes/navbar.php'; ?>

    <!-- CONTENT -->
    <div class="content">
        <h1>Visi & Misi</h1>
        <div class="section">
            <h2>Visi</h2>
            <p>Mewujudkan Generasi Unggul, Berakhlak, dan Berprestasi</p>
            <h2>Misi</h2>
            <ol>
                <li>Menyelenggarakan pendidikan yang berkualitas dan berakhlak mulia.</li>
                <li>Mengembangkan potensi siswa secara optimal dan berkelanjutan.</li>
                <li>Meningkatkan kerjasama dengan orang tua, masyarakat, dan dunia usaha.</li>
                <li>Mendorong inovasi dan prestasi di bidang akademik dan non-akademik.</li>
            </ol>
        </div>
    </div>
</body>
</html>