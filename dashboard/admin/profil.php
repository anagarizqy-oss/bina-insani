<?php
// dashboard/admin/profil.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['admin']);

$stmt = $pdo->prepare("SELECT nama_lengkap, username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - SMA BINA INSANI</title>
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
            <a href="berita.php">Berita</a>
            <a href="profil.php" style="border-bottom: 2px solid white;">Profil</a>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Profil Pengguna</h2>
        <div class="card" style="text-align: center; max-width: 500px; margin: 2rem auto;">
            <img src="../../assets/logo-navbar.png" alt="Foto Profil" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 1.5rem;">
            <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($user['nama_lengkap']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Peran:</strong> Admin</p>
        </div>
    </div>
</body>
</html>