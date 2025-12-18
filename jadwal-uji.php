<?php
// jadwal-uji.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Ujian - SMA BINA INSANI WONOGIRI</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background: #f0f7ff;
            color: #2575fc;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #f9fbff;
        }

        .note {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Jadwal Ujian</h1>
        <div class="section">
            <p>Berikut adalah jadwal ujian semester ganjil 2025/2026:</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Hari/Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Senin, 15 Sep 2025</td>
                        <td>Bahasa Indonesia</td>
                        <td>X, XI, XII</td>
                        <td>08.00–10.00</td>
                    </tr>
                    <tr>
                        <td>Selasa, 16 Sep 2025</td>
                        <td>Matematika</td>
                        <td>X, XI, XII</td>
                        <td>08.00–10.00</td>
                    </tr>
                    <tr>
                        <td>Rabu, 17 Sep 2025</td>
                        <td>IPA</td>
                        <td>X, XI</td>
                        <td>08.00–10.00</td>
                    </tr>
                    <tr>
                        <td>Kamis, 18 Sep 2025</td>
                        <td>IPS</td>
                        <td>XII</td>
                        <td>08.00–10.00</td>
                    </tr>
                    <tr>
                        <td>Jumat, 19 Sep 2025</td>
                        <td>Bahasa Inggris</td>
                        <td>X, XI, XII</td>
                        <td>08.00–10.00</td>
                    </tr>
                </tbody>
            </table>

            <div class="note">
                <strong>Catatan:</strong> Jadwal dapat berubah sesuai kebutuhan. Siswa diwajibkan hadir 15 menit sebelum ujian dimulai.
            </div>
        </div>
    </div>
</body>
</html>