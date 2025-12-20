<?php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

$stmt = $pdo->prepare("SELECT u.nama_lengkap, u.username, g.nip, g.kelas_wali FROM users u JOIN guru g ON u.id = g.user_id WHERE u.id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - Guru</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="navbar-logo">
            <h1>SMA BINA INSANI</h1>
        </div>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="profil.php" style="border-bottom: 2px solid white;">Profil</a>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Profil Pengguna</h2>
        <div class="card" style="text-align: center; max-width: 500px; margin: 2rem auto;">
            <img src="../../assets/logo-navbar.png" alt="Foto Profil" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 1.5rem;">
            <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama_lengkap']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>NIP:</strong> <?= htmlspecialchars($user['nip'] ?? '-') ?></p>
            <p><strong>Kelas Wali:</strong> <?= $user['kelas_wali'] ? htmlspecialchars($user['kelas_wali']) : 'Bukan wali kelas' ?></p>
        </div>
    </div>
</body>
</html>
