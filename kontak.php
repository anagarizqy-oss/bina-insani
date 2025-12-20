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
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* === BODY DENGAN GRADIENT SAMA SEPERTI INDEX.PHP === */
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
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

        .contact-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-top: 1.5rem;
        }

        .contact-info {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .info-item {
            flex: 1;
            min-width: 200px;
            padding: 1.5rem;
            background: #f9fbff;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.2s;
        }

        .info-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .info-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .info-title {
            font-weight: bold;
            color: #2575fc;
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }

        .info-value {
            color: #333;
            line-height: 1.5;
            margin-top: 0.5rem;
        }

        /* MAP STYLING */
        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            margin: 2rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* BUTTONS STYLING */
        .contact-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-contact {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #e3f2fd;
            color: #2575fc;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn-contact:hover {
            background: #bbdefb;
        }

        .btn-contact i {
            font-size: 1.2rem;
        }

        /* RESPONSIF */
        @media (max-width: 768px) {
            .contact-info {
                flex-direction: column;
                gap: 1rem;
            }

            .info-item {
                min-width: 100%;
            }

            .map-container {
                height: 300px;
            }

            .contact-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
   <?php include 'includes/navbar.php'; ?>


    <!-- CONTENT -->
    <div class="content">
        <h1>Kontak & Lokasi Sekolah</h1>

        <div class="contact-card">
            <div class="contact-info">
                <!-- ALAMAT -->
                <div class="info-item">
                    <div class="info-icon">ðŸ“</div>
                    <div class="info-title">Alamat</div>
                    <div class="info-value">
                        Jl. Raya Wonogiri, Desa Boto, Kec. Wonogiri<br>
                        Kab. Wonogiri, Jawa Tengah
                    </div>
                </div>

                <!-- TELEPON -->
                <div class="info-item">
                    <div class="info-icon">ðŸ“ž</div>
                    <div class="info-title">Telepon</div>
                    <div class="info-value">
                        (0273) 123456<br>
                        <a href="https://wa.me/6289675316655" target="_blank" style="color: #2575fc; text-decoration: none;">
                            +62 89675316655
                        </a>
                    </div>
                </div>

                <!-- EMAIL -->
                <div class="info-item">
                    <div class="info-icon">âœ‰ï¸</div>
                    <div class="info-title">Email</div>
                    <div class="info-value">
                        <a href="mailto:info@smabinainsani.sch.id" style="color: #2575fc; text-decoration: none;">
                            info@smabinainsani.sch.id
                        </a>
                    </div>
                </div>
            </div>

            <!-- GOOGLE MAPS -->
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7604533250624!2d110.94175227476632!3d-7.815162292205435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a2f65ca178011%3A0x16f21001612bcbd!2sSMP%2FSMA%20Bina%20Insani%20Wonogiri!5e0!3m2!1sid!2sid!4v1765965066969!5m2!1sid!2sid" 
                    width="600" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- CONTACT BUTTONS -->
            <div class="contact-buttons">
                <!-- WHATSAPP -->
                <a href="https://wa.me/6289675316655" target="_blank" class="btn-contact">
                    <span>ðŸ“ž</span>
                    <span>+62 89675316655</span>
                </a>

                <!-- INSTAGRAM -->
                <a href="https://www.instagram.com/smabiw/" target="_blank" class="btn-contact">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#E1306C">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.23 1.664-4.769 4.919-4.917 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.28-.073 1.688-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.28.058 1.688.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.688.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.28-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.439s.645 1.439 1.441 1.439c.795 0 1.439-.645 1.439-1.439s-.644-1.439-1.439-1.439z"/>
                    </svg>
                    <span>@smabiw</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
