<?php
// masukan.php
include 'config/db.php';
include 'includes/csrf.php';
include 'includes/navbar.php';

// Inisialisasi
$error = '';
$success = '';

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_masukan'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $error = "Permintaan tidak valid.";
    } else {
        $nama   = clean($_POST['nama']);
        $email  = clean($_POST['email']);
        $subjek = clean($_POST['subjek']);
        $pesan  = clean($_POST['pesan']);

        if (empty($nama) || empty($pesan)) {
            $error = "Nama dan Pesan wajib diisi.";
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO masukan_saran (nama, email, subjek, pesan)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$nama, $email, $subjek, $pesan]);
                $success = "Terima kasih atas masukan Anda!";
            } catch (Exception $e) {
                $error = "Gagal menyimpan masukan.";
            }
        }
    }
}

$csrf_token = generate_token();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukan & Saran - SMA BINA INSANI WONOGIRI</title>
    <link rel="stylesheet" href="assets/style.css">

    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .content {
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .section {
            background: transparent;
        }
    </style>
</head>

<body>

    <div class="content">
        <div id="masukan" class="section">
            <h2 style="color:#fff; text-align:center;">Masukan & Saran</h2>
            <?php if ($error): ?>
                <div style="background:#ffebee;color:#c62828;padding:10px;border-radius:6px;margin:15px 0;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background:#e8f5e9;color:#2e7d32;padding:10px;border-radius:6px;margin:15px 0;">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <div style="background:white;padding:2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.1);">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div style="margin-bottom: 1.2rem;">
                        <label for="nama" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Nama Lengkap *</label>
                        <input type="text" name="nama" id="nama" placeholder="Contoh: Andi Prasetyo" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 1.2rem;">
                        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Email (Opsional)</label>
                        <input type="email" name="email" id="email" placeholder="Contoh: andi@email.com" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 1.2rem;">
                        <label for="subjek" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Subjek (Opsional)</label>
                        <input type="text" name="subjek" id="subjek" placeholder="Contoh: Saran Penyempurnaan Website" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 1.2rem;">
                        <label for="pesan" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Pesan *</label>
                        <textarea name="pesan" id="pesan" rows="5" placeholder="Tuliskan masukan atau saran Anda..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"></textarea>
                    </div>

                    <button type="submit" name="submit_masukan"
                        style="background:#2575fc;color:white;border:none;padding:12px 25px;border-radius:6px;font-weight:bold;">
                        Kirim Masukan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer style="text-align:center;color:#eee;padding:2rem 1rem;">
        &copy; <?= date('Y') ?> SMA Bina Insani Wonogiri
    </footer>

</body>

</html>