<?php
// tenaga-kependidikan.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Staf Tenaga Kependidikan - SMA BINA INSANI WONOGIRI</title>
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

        .staff-list {
            margin-top: 1.5rem;
        }

        .staff-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .staff-role {
            font-weight: bold;
            color: #2575fc;
        }

        .staff-name {
            color: #333;
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>


    <div class="content">
        <h1>Staf Tenaga Kependidikan</h1>
        <div class="section">
            <p>Terdiri dari 10 staf administrasi, 5 tenaga perpustakaan, dan 8 tenaga kebersihan yang profesional dan ramah.</p>
            <p>Mereka mendukung kelancaran operasional sekolah setiap hari.</p>
            
            <div class="staff-list">
                <div class="staff-item">
                    <div class="staff-role">Kepala Tata Usaha</div>
                    <div class="staff-name">Dewi Lestari</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Staff Administrasi</div>
                    <div class="staff-name">Andi Prasetyo</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Staff Administrasi</div>
                    <div class="staff-name">Siti Nurhayati</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Staff Administrasi</div>
                    <div class="staff-name">Rudi Hartono</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Staff Administrasi</div>
                    <div class="staff-name">Maya Sari</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Staff Administrasi</div>
                    <div class="staff-name">Budi Prasetyo</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Kepala Perpustakaan</div>
                    <div class="staff-name">Lina Wijayanti</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Staff Perpustakaan</div>
                    <div class="staff-name">5 Orang</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Petugas Kebersihan</div>
                    <div class="staff-name">8 Orang</div>
                </div>
                <div class="staff-item">
                    <div class="staff-role">Petugas Keamanan</div>
                    <div class="staff-name">2 Orang</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>