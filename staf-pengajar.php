<?php
// staf-pengajar.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Staf Pengajar - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: url('assets/foto_guru.jpeg') no-repeat center center;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Header judul */
        .page-header {
            text-align: center;
            color: #2575fc;
            padding: 2rem 1rem;
            margin: 0;
            /* HAPUS background-color */
            /* background-color: rgba(255, 255, 255, 0.9); */
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

        /* Deskripsi dan Grid Staf â€” tanpa kotak putih */
        .staff-container {
            padding: 1.5rem 1rem;
            margin: 0 auto;
            max-width: 100%;
        }

        .staff-description {
            line-height: 1.6;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #333;
            /* HAPUS background-color */
            /* background-color: rgba(255, 255, 255, 0.85); */
            padding: 1rem;
            border-radius: 8px;
            /* Tambahkan text-shadow agar teks jelas di atas foto */
        }

        /* Grid yang responsif dan memenuhi lebar */
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .staff-card {
            background: #ffffff;
            /* <-- INI YANG DIUBAH: background putih */
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 220px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .staff-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .staff-image-placeholder {
            width: 130px;
            height: 130px;
            background-color: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .staff-name {
            font-weight: bold;
            color: #2575fc;
            margin: 0.5rem 0 0.2rem 0;
            font-size: 0.95rem;
            text-align: center;
        }

        .staff-subject {
            color: #666;
            font-size: 0.8rem;
            font-weight: normal;
            text-align: center;
        }

        /* Animasi */
        @keyframes fadeInBounce {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            60% {
                opacity: 1;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsif */
        @media (max-width: 768px) {
            .staff-container {
                padding: 1rem 0.5rem;
            }

            .staff-description {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .staff-card {
                min-height: 200px;
                padding: 0.75rem;
            }

            .staff-image-placeholder {
                width: 100px;
                height: 100px;
                font-size: 0.75rem;
            }

            .staff-name {
                font-size: 0.9rem;
            }

            .staff-subject {
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Header Judul -->
    <div class="page-header">
        <h1>Staf Pengajar</h1>
    </div>

    <div class="staff-grid">
        <!-- Contoh Data Staf -->
        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Arsala Zamarudy, S.Pd.I.</div>
            <div class="staff-subject">Pustakawan</div>
        </div>

        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Citra Mar'atu Sholikha, A.Md.Si.</div>
            <div class="staff-subject">Pustakawan</div>
        </div>

        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Frida Zanu Ayu Kadarwati, S.S.</div>
            <div class="staff-subject">Administrasi Umum</div>
        </div>

        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Lolita Windanuri Khurata Ayun, S.Kep.</div>
            <div class="staff-subject">Staff Kesehatan</div>
        </div>

        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Muhammad Ashari, S.Kom.</div>
            <div class="staff-subject">Operator Sekolah</div>
        </div>

        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Muhammad Ghadavi, S.Pd.</div>
            <div class="staff-subject">Bendara BOS</div>
        </div>

        <div class="staff-card">
            <div class="staff-image-placeholder">Tidak Ada Gambar</div>
            <div class="staff-name">Rifqi Riza, S.Kom.</div>
            <div class="staff-subject">Bendara BOS</div>
        </div>

        <!-- Tambahkan data staf lainnya di sini -->

    </div>
    </div>
</body>

</html>
