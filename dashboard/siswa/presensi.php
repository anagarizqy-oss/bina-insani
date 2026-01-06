<?php
// dashboard/siswa/presensi.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];

// Get Student ID
$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();
$siswa_id = $siswa['id'];

// Get Presence Data
$stmt_presensi = $pdo->prepare("SELECT * FROM presensi WHERE siswa_id = ? ORDER BY tanggal DESC");
$stmt_presensi->execute([$siswa_id]);
$presensi_data = $stmt_presensi->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            Presensi Saya
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Riwayat Kehadiran</h2>

        <div class="table-container">
            <?php if (count($presensi_data) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($presensi_data as $row): ?>
                            <tr>
                                <td><?= date('d F Y', strtotime($row['tanggal'])) ?></td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    $badge = 'danger';
                                    if ($status == 'Hadir') $badge = 'success';
                                    elseif ($status == 'Izin' || $status == 'Sakit') $badge = 'warning';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= $status ?></span>
                                </td>
                                <td>-</td> <!-- Bisa ditambah kolom keterangan di DB jika ada -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert warning">Belum ada data presensi.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>

</html>