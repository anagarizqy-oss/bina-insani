<?php
// informasi.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* === BODY DENGAN GRADIENT SAMA SEPERTI INDEX.PHP === */
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .content {
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* === INFO GRID (2 KOLOM) === */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .info-title {
            color: #2575fc;
            font-size: 1.4rem;
            margin-bottom: 1.2rem;
            font-weight: bold;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid #f0f7ff;
        }

        .info-content {
            line-height: 1.7;
            color: #333;
        }

        .info-content ul {
            padding-left: 1.3rem;
            margin: 1rem 0;
        }

        .info-content li {
            margin: 0.6rem 0;
            position: relative;
            padding-left: 1.2rem;
        }

        .info-content li::before {
            content: '•';
            color: #2575fc;
            font-weight: bold;
            position: absolute;
            left: 0;
            top: 0.2rem;
        }

        .info-content p {
            margin: 0.8rem 0;
        }

        /* RESPONSIF */
        @media (max-width: 768px) {
            .content {
                padding: 2rem 1rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .info-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR DENGAN LOGO -->
    <?php include 'includes/navbar.php'; ?>

    <!-- CONTENT -->
    <div class="content">
        <h1>Informasi Penting</h1>

        <div class="info-grid">
            <!-- PENGUMUMAN -->
            <div class="info-card">
                <div class="info-title">📢 Pengumuman</div>
                <div class="info-content">
                    <ul>
                        <li>Jadwal MOS tahun ajaran 2025/2026: <strong>15–20 Juli 2025</strong></li>
                        <li>Libur semester ganjil: <strong>20 Desember 2025 – 6 Januari 2026</strong></li>
                        <li>Pelaksanaan try out nasional: <strong>10–12 Februari 2026</strong></li>
                        <li>Upacara peringatan HUT RI ke-80: <strong>17 Agustus 2025</strong></li>
                    </ul>
                    <p>
                        Informasi lebih lanjut dapat dilihat di papan pengumuman sekolah 
                        atau melalui aplikasi resmi SMA Bina Insani.
                    </p>
                </div>
            </div>

            <!-- KELULUSAN -->
            <div class="info-card">
                <div class="info-title">🎓 Kelulusan</div>
                <div class="info-content">
                    <p>
                        <strong>Tahun 2024:</strong> <span style="color: #2575fc; font-weight: bold;">100% LULUS</span> UNBK
                    </p>
                    <p>
                        <strong>85%</strong> lulusan diterima di Perguruan Tinggi Negeri (PTN) 
                        melalui jalur SNMPTN, SBMPTN, dan seleksi mandiri.
                    </p>
                    <p>
                        Alumni kami diterima di universitas ternama seperti:
                    </p>
                    <ul>
                        <li>Universitas Indonesia (UI)</li>
                        <li>Universitas Gadjah Mada (UGM)</li>
                        <li>Institut Teknologi Bandung (ITB)</li>
                        <li>Universitas Diponegoro (Undip)</li>
                    </ul>
                    <p>
                        Selamat kepada seluruh lulusan! Teruslah berkarya dan jadilah kebanggaan bangsa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>