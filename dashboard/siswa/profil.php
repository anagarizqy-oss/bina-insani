<?php
// dashboard/siswa/profil.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$stmt = $pdo->prepare("
    SELECT u.nama_lengkap, u.username, s.nis, s.kelas 
    FROM users u 
    JOIN siswa s ON u.id = s.user_id 
    WHERE u.id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa - SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <!-- Navbar dengan Logo -->
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
        <h2>Profil Saya</h2>
        <div class="card" style="text-align: center; max-width: 500px; margin: 2rem auto;">
            <img src="../../assets/logo-navbar.png" alt="Foto Profil" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 1.5rem;">
            <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($user['nama_lengkap']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>NIS:</strong> <?= htmlspecialchars($user['nis'] ?? '–') ?></p>
            <p><strong>Kelas:</strong> <?= htmlspecialchars($user['kelas'] ?? '–') ?></p>
            <p><strong>Peran:</strong> Siswa</p>
        </div>
    </div>
</body>
</html>