<?php
// fasilitas.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Fasilitas - SMA BINA INSANI WONOGIRI</title>
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

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 0.5rem 0;
            position: relative;
            padding-left: 20px;
        }

        li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #2575fc;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'includes/navbar.php'; ?>

    <!-- CONTENT -->
    <div class="content">
        <h1>Fasilitas</h1>
        <div class="section">
            <ul>
                <li>Ruang kelas ber-AC dengan proyektor</li>
                <li>Laboratorium IPA (Fisika, Kimia, Biologi)</li>
                <li>Laboratorium Komputer berbasis ICT</li>
                <li>Perpustakaan digital</li>
                <li>Lapangan olahraga (basket, voli, futsal)</li>
                <li>Musholla dan kantin sehat</li>
            </ul>
        </div>
    </div>
</body>
</html>