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
            background: url('assets/foto_guru.jpeg') no-repeat center center;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content {
            padding: 2.5rem 1.5rem;
            margin: 0 auto;
            max-width: 100%;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            /* Extra bold */
            text-shadow:
                0 0 8px rgba(0, 0, 0, 0.8),
                0 0 8px rgba(0, 0, 0, 0.52),
                0 0 8px #ffffffff;
            /* Outline putih tipis */
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInBounce 1s ease-out 0.3s forwards;
        }


        .section {
            background: rgba(255, 255, 255, 0.92);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 900px;
        }

        .section p {
            text-align: center;
            margin-bottom: 2rem;
            color: #555;
        }

        /* Bagan Organisasi */
        .org-chart {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2.5rem;
        }

        .org-level {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .org-node {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 1.2rem 1rem;
            text-align: center;
            min-width: 220px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }

        .org-node:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .org-role {
            font-weight: 700;
            color: #2575fc;
            margin-bottom: 0.4rem;
            font-size: 0.95rem;
        }

        .org-name {
            color: #333;
            font-size: 0.95rem;
        }

        /* Garis penghubung (opsional, sederhana) */
        .connector {
            height: 20px;
            width: 2px;
            background: #2575fc;
            margin: 0 auto;
        }

        @keyframes fadeInBounce {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            60% {
                opacity: 1;
                transform: translateY(-8px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            .org-node {
                min-width: 180px;
                padding: 1rem 0.7rem;
            }

            .org-level {
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Struktur Organisasi</h1>
        <div class="section">
            <p>Berikut adalah struktur organisasi SMA Bina Insani Wonogiri periode 2025/2026:</p>

            <div class="org-chart">
                <!-- Level 1: Kepala Sekolah -->
                <div class="org-level">
                    <div class="org-node">
                        <div class="org-role">Kepala Sekolah</div>
                        <div class="org-name">Drs. Ahmad Fauzi, M.Pd.</div>
                    </div>
                </div>

                <!-- Garis penghubung -->
                <div class="connector"></div>

                <!-- Level 2: Wakil Kepala & TU -->
                <div class="org-level">
                    <div class="org-node">
                        <div class="org-role">Wakil Kepala Bidang Kurikulum</div>
                        <div class="org-name">Dra. Siti Rahayu</div>
                    </div>
                    <div class="org-node">
                        <div class="org-role">Wakil Kepala Bidang Kesiswaan</div>
                        <div class="org-name">Budi Santoso, S.Pd.</div>
                    </div>
                    <div class="org-node">
                        <div class="org-role">Wakil Kepala Bidang Sarpras</div>
                        <div class="org-name">Agus Prasetyo, S.T.</div>
                    </div>
                    <div class="org-node">
                        <div class="org-role">Kepala Tata Usaha</div>
                        <div class="org-name">Dewi Lestari</div>
                    </div>
                </div>

                <!-- Garis penghubung -->
                <div class="connector"></div>

                <!-- Level 3: Komite Sekolah -->
                <div class="org-level">
                    <div class="org-node">
                        <div class="org-role">Ketua Komite Sekolah</div>
                        <div class="org-name">H. Suryono</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>