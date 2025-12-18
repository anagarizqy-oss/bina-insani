<?php
// struktur.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struktur Organisasi - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background: url('assets/foto_guru.jpeg') ;
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

        .structure-list {
            display: grid;
            gap: 1.2rem;
            margin-top: 1rem;
        }

        .role-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .role-title {
            font-weight: bold;
            color: #2575fc;
        }

        .role-name {
            color: #333;
        }
    </style>
</head>
<body>
   <?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Struktur Organisasi</h1>
        <div class="section">
            <p>Berikut adalah struktur organisasi SMA Bina Insani Wonogiri periode 2025/2026:</p>
            
            <div class="structure-list">
                <div class="role-item">
                    <div class="role-title">Kepala Sekolah</div>
                    <div class="role-name">Drs. Ahmad Fauzi, M.Pd.</div>
                </div>
                <div class="role-item">
                    <div class="role-title">Wakil Kepala Bidang Kurikulum</div>
                    <div class="role-name">Dra. Siti Rahayu</div>
                </div>
                <div class="role-item">
                    <div class="role-title">Wakil Kepala Bidang Kesiswaan</div>
                    <div class="role-name">Budi Santoso, S.Pd.</div>
                </div>
                <div class="role-item">
                    <div class="role-title">Wakil Kepala Bidang Sarana & Prasarana</div>
                    <div class="role-name">Agus Prasetyo, S.T.</div>
                </div>
                <div class="role-item">
                    <div class="role-title">Kepala Tata Usaha</div>
                    <div class="role-name">Dewi Lestari</div>
                </div>
                <div class="role-item">
                    <div class="role-title">Ketua Komite Sekolah</div>
                    <div class="role-name">H. Suryono</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>