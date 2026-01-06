<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-graduation-cap"></i> <span>SISWA</span>
        <button class="close-sidebar" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
    </div>

    <div class="user-info">
        <p>Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Siswa') ?></p>
        <small>SMA BINA INSANI</small>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="presensi.php" class="<?= $current_page == 'presensi.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i> Presensi
            </a>
        </li>
        <li>
            <a href="nilai.php" class="<?= $current_page == 'nilai.php' ? 'active' : '' ?>">
                <i class="fas fa-star"></i> Nilai
            </a>
        </li>
        <li>
            <a href="spp.php" class="<?= $current_page == 'spp.php' ? 'active' : '' ?>">
                <i class="fas fa-money-bill-wave"></i> SPP
            </a>
        </li>
        <li>
            <a href="../../logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>