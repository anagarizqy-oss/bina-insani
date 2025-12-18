<?php
// identitas.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Identitas Sekolah - SMA BINA INSANI WONOGIRI</title>
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

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 0.8rem 0;
            font-size: 1.1rem;
        }

        strong {
            color: #2575fc;
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Identitas Sekolah</h1>
        <div class="section">
            <ul>
                <li><strong>Nama Sekolah</strong>: SMA Bina Insani Wonogiri</li>
                <li><strong>NPSN</strong>: 20321007</li>
                <li><strong>Alamat</strong>: Jl. Raya Wonogiri, Desa Boto, Kec. Wonogiri, Kab. Wonogiri, Jawa Tengah</li>
                <li><strong>Akreditasi</strong>: A (Unggul)</li>
                <li><strong>Kurikulum</strong>: Kurikulum Merdeka</li>
                <li><strong>Tahun Berdiri</strong>: 2005</li>
            </ul>
        </div>
    </div>
</body>
</html>