<?php
// profil-sekolah.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Sekolah - SMA BINA INSANI WONOGIRI</title>
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
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'includes/navbar.php'; ?>

    <!-- CONTENT -->
    <div class="content">
        <h1>Profil Sekolah</h1>
        <div class="section">
            <p>SMA Bina Insani Wonogiri adalah lembaga pendidikan menengah atas yang berkomitmen untuk mencetak generasi unggul, berakhlak mulia, dan berprestasi di bidang akademik maupun non-akademik.</p>
            <p>Didirikan pada tahun 2005, sekolah ini telah meluluskan ribuan siswa yang sukses di perguruan tinggi dan dunia kerja.</p>
        </div>
    </div>
</body>
</html>