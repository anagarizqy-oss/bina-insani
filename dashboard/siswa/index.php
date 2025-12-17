<?php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$siswa_id = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="navbar-logo">
            <h1>SMA BINA INSANI</h1>
        </div>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="profil.php">Profil</a>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>
    <div class="dashboard">
        <h2>Halo, <?= $_SESSION['nama'] ?>!</h2>

        <div class="card">
            <h3>Nilai Anda</h3>
            <table>
                <tr><th>Mata Pelajaran</th><th>Nilai</th><th>Semester</th></tr>
                <?php
                $nilai = $pdo->prepare("SELECT * FROM nilai WHERE siswa_id = ?");
                $nilai->execute([$siswa_id]);
                while ($row = $nilai->fetch()) {
                    echo "<tr><td>{$row['mapel']}</td><td>{$row['nilai']}</td><td>{$row['semester']}</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>