<?php
// kalender-akademik.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kalender Akademik - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/css/style.css">
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

        .calendar-info {
            line-height: 1.6;
            margin: 1rem 0;
        }

        .semester-box {
            background: #f0f7ff;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .semester-title {
            color: #2575fc;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .semester-dates {
            color: #666;
            margin: 0.5rem 0;
        }

        .holiday-list {
            margin-top: 1.5rem;
            padding-left: 1.5rem;
        }

        .holiday-item {
            margin: 0.5rem 0;
            color: #333;
        }

        .holiday-date {
            font-weight: bold;
            color: #2575fc;
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

    <div class="content">
        <h1>Kalender Akademik</h1>
        <p style="text-align: center; margin-bottom: 1.5rem;">
            <a href="javascript:history.back()" style="color: #2575fc; text-decoration: none; font-weight: bold;">â† Kembali ke Agenda</a>
        </p>
        
        <div class="section">
            <p>Tahun ajaran 2025/2026:</p>
            
            <!-- ... isi konten ... -->

            <!-- TOMBOL DOWNLOAD (opsional) -->
            <div style="margin-top: 1.5rem; text-align: center;">
                <a href="assets/kalender-2025.pdf" target="_blank" 
                   style="display: inline-block; background: #2575fc; color: white; padding: 8px 20px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                    ðŸ“¥ Download Kalender Akademik (PDF)
                </a>
            </div>
        </div>
    </div>
</body>
</html>
