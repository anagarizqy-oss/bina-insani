<?php
include 'config/db.php';
include 'includes/navbar.php';
 ?>


if ($_POST) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $error = "Permintaan tidak valid.";
    } else {
        $username = clean($_POST['username']);
        $password = $_POST['password'];
        $nama = clean($_POST['nama']);

        // Cek apakah admin sudah ada
        $exists = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
        if ($exists > 0) {
            $error = "Admin sudah ada! Hapus file ini setelah selesai.";
        } elseif (strlen($password) < 6) {
            $error = "Password minimal 6 karakter.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?,?, 'admin', ?)")
                ->execute([$username, $hash, $nama]);
            $success = "Admin berhasil dibuat! Silakan login.";
        }
    }
}
$token = generate_token();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin Pertama</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Buat Akun Admin</h1>
        <p class="subtitle">Hanya untuk pertama kali!</p>
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $token ?>">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password (min 6)" required>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <button type="submit">Buat Admin</button>
        </form>
    </div>
</body>
</html>
