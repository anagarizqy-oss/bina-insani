<?php
// dashboard/siswa/spp.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();
$siswa_id = $siswa['id'];

// Get SPP Data
$stmt_spp = $pdo->prepare("SELECT * FROM spp WHERE siswa_id = ? ORDER BY id DESC"); // Assuming chronological order or add order by month index if needed
$stmt_spp->execute([$siswa_id]);
$spp_data = $stmt_spp->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            Status Pembayaran
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Status Pembayaran SPP</h2>

        <div class="card-grid">
            <?php if (count($spp_data) > 0): ?>
                <?php foreach ($spp_data as $row): ?>
                    <div class="card" style="text-align: center;">
                        <h3><?= htmlspecialchars($row['bulan']) ?></h3>
                        <div class="badge <?= $row['status'] == 'Lunas' ? 'success' : 'danger' ?>"
                            style="font-size: 1.2rem; margin-top: 10px; display: inline-block;">
                            <?= $row['status'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card" style="width: 100%;">
                    <p>Belum ada data tagihan SPP.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>

</html>