<?php
// kontak.php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background: #f9fbff;
            display: block;
        }
        .section {
            padding: 2.5rem 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        .info-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin: 2rem 0;
        }
        .info-box {
            flex: 1;
            min-width: 250px;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .info-box h3 {
            color: #2575fc;
            margin-bottom: 1rem;
        }
        .info-box p {
            margin: 0.5rem 0;
            display: flex;
            align-items: flex-start;
        }
        .info-box .icon {
            margin-right: 10px;
            color: #2575fc;
            font-weight: bold;
        }
        .map-container {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            margin: 1.5rem 0;
        }
        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
        }
        a.back-home {
            display: inline-block;
            margin-top: 1rem;
            color: #2575fc;
            text-decoration: none;
            font-weight: bold;
        }
        a.back-home:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="section">
        <h2 style="text-align: center; color: #2575fc;">Kontak & Lokasi Sekolah</h2>
        
        <div class="info-contact">
            <div class="info-box">
                <h3>Informasi Sekolah</h3>
                <p><span class="icon">📍</span> Jl. Raya Wonogiri, Desa Boto, Kec. Wonogiri, Kab. Wonogiri, Jawa Tengah</p>
                <p><span class="icon">📞</span> (0273) 123456</p>
                <p><span class="icon">✉️</span> info@smabinainsani.sch.id</p>
                <p><span class="icon">🌐</span> www.smabinainsani.sch.id</p>
            </div>

            <div class="info-box">
                <h3>Jam Operasional</h3>
                <p><span class="icon">🕗</span> Senin - Jumat: 07.00 - 15.00 WIB</p>
                <p><span class="icon">📅</span> Sabtu: 07.00 - 12.00 WIB</p>
                <p><span class="icon">❌</span> Minggu & Libur Nasional: Tutup</p>
            </div>
        </div>

        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7604533250624!2d110.94175227476632!3d-7.815162292205435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a2f65ca178011%3A0x16f21001612bcbd!2sSMP%2FSMA%20Bina%20Insani%20Wonogiri!5e0!3m2!1sid!2sid!4v1765883973471!5m2!1sid!2sid" 
                width="600" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <a href="index.php" class="back-home">← Kembali ke Beranda</a>
    </div>
</body>
</html>