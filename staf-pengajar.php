<?php
// staf-pengajar.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Staf Pengajar - SMA BINA INSANI WONOGIRI</title>
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
            line-height: 1.6;
            margin: 1rem 0;
        }

        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.2rem;
            margin-top: 1.5rem;
        }

        .staff-card {
            background: #f9fbff;
            padding: 1.2rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .staff-name {
            font-weight: bold;
            color: #2575fc;
            margin: 0.5rem 0;
        }

        .staff-subject {
            color: #666;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Staf Pengajar</h1>
        <div class="section">
            <p>SMA Bina Insani Wonogiri memiliki 35 guru tetap yang 90% di antaranya berpendidikan S2 dan telah mengikuti pelatihan kurikulum merdeka.</p>
            
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-name">Dra. Siti Rahayu</div>
                    <div class="staff-subject">Bahasa Indonesia</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Drs. Agus Prasetyo</div>
                    <div class="staff-subject">Matematika</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Rina Wijayanti, S.Pd.</div>
                    <div class="staff-subject">Bahasa Inggris</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Budi Santoso, S.Pd.</div>
                    <div class="staff-subject">Pendidikan Agama</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Lina Kurnia, M.Pd.</div>
                    <div class="staff-subject">Fisika</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Hendra Setiawan, S.Si.</div>
                    <div class="staff-subject">Kimia</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Dewi Lestari, S.Pd.</div>
                    <div class="staff-subject">Biologi</div>
                </div>
                <div class="staff-card">
                    <div class="staff-name">Ahmad Rifai, S.Kom.</div>
                    <div class="staff-subject">TIK</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>