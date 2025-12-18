<?php
// libur-nasional.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Libur Nasional - SMA BINA INSANI WONOGIRI</title>
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

        .holiday-list {
            list-style: none;
            padding: 0;
        }

        .holiday-item {
            display: flex;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .holiday-date {
            font-weight: bold;
            color: #2575fc;
            min-width: 120px;
        }

        .holiday-name {
            color: #333;
            flex: 1;
        }

        .holiday-type {
            color: #666;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

    <div class="content">   
        <h1>Libur Nasional</h1>
        <div class="section">
            <p>Berdasarkan ketentuan pemerintah, berikut adalah hari libur nasional tahun 2025:</p>
            
            <ul class="holiday-list">
          <li class="holiday-item">
    <div class="holiday-date">1 Jan 2025</div>
    <div class="holiday-name">Tahun Baru Masehi</div>
    <div class="holiday-type">Hari Libur Nasional</div>
</li>

                <li class="holiday-item">
                    <div class="holiday-date">14 Feb 2025</div>
                    <div class="holiday-name">Valentine’s Day</div>
                    <div class="holiday-type">Hari Kasih Sayang (tidak libur)</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">27 Mar 2025</div>
                    <div class="holiday-name">Hari Raya Nyepi</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">1 Apr 2025</div>
                    <div class="holiday-name">Hari Buruh Internasional</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">18 Apr 2025</div>
                    <div class="holiday-name">Wafat Isa Al-Masih</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">1 Mei 2025</div>
                    <div class="holiday-name">Hari Pendidikan Nasional</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">23 Mei 2025</div>
                    <div class="holiday-name">Hari Raya Waisak</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">1 Jun 2025</div>
                    <div class="holiday-name">Hari Lahir Pancasila</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">17 Jun 2025</div>
                    <div class="holiday-name">Hari Raya Idul Fitri</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">17 Agust 2025</div>
                    <div class="holiday-name">HUT RI ke-80</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
                <li class="holiday-item">
                    <div class="holiday-date">25 Des 2025</div>
                    <div class="holiday-name">Natal</div>
                    <div class="holiday-type">Hari Libur Nasional</div>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>