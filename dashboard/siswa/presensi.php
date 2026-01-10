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

// Get Subject Attendance History (Absensi Mapel)
$query = "
    SELECT p.*, s.tanggal, s.waktu_buka, g.nama_lengkap as nama_guru, g.mata_pelajaran
    FROM presensi_mapel p
    JOIN absensi_sesi s ON p.sesi_id = s.id
    JOIN jadwal_pelajaran j ON s.jadwal_id = j.id
    JOIN guru g ON j.guru_id = g.id
    WHERE p.siswa_id = ?
    ORDER BY s.tanggal DESC, p.waktu_hadir DESC
";
$stmt_hist = $pdo->prepare($query);
$stmt_hist->execute([$siswa_id]);
$history = $stmt_hist->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            <span>Riwayat Presensi</span>
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Riwayat Kehadiran Pelajaran</h2>

        <div class="card">
            <div class="table-container">
                <?php if (count($history) > 0): ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                                <th style="padding: 12px; text-align: left;">Tanggal</th>
                                <th style="padding: 12px; text-align: left;">Mata Pelajaran</th>
                                <th style="padding: 12px; text-align: left;">Guru</th>
                                <th style="padding: 12px; text-align: left;">Waktu Absen</th>
                                <th style="padding: 12px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $row): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; color: #555;">
                                        <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                    </td>
                                    <td style="padding: 12px; font-weight: 500;">
                                        <?= htmlspecialchars($row['mata_pelajaran']) ?>
                                    </td>
                                    <td style="padding: 12px; color: #666;">
                                        <?= htmlspecialchars($row['nama_guru']) ?>
                                    </td>
                                    <td style="padding: 12px; color: #666;">
                                        <?= date('H:i', strtotime($row['waktu_hadir'])) ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <?php
                                        $status = $row['status'];
                                        $badge_color = 'success'; // Default green for Hadir
                                        if ($status == 'Izin') $badge_color = 'warning';
                                        if ($status == 'Sakit') $badge_color = 'info';
                                        if ($status == 'Alpha') $badge_color = 'danger';
                                        ?>
                                        <span class="badge <?= $badge_color ?>"><?= $status ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: 20px; text-align: center; color: #888;">
                        <i class="fas fa-history" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        Belum ada riwayat absensi pelajaran.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>

</html>