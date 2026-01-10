<?php
// dashboard/guru/detail_absensi.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

$sesi_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$sesi_id) {
    die("ID Sesi tidak valid.");
}

// 1. Get Session & Jadwal Info
$stmt_sesi = $pdo->prepare("
    SELECT s.*, j.jam_mulai, j.jam_selesai, k.nama_kelas, k.id as kelas_id, gu.mata_pelajaran
    FROM absensi_sesi s
    JOIN jadwal_pelajaran j ON s.jadwal_id = j.id
    JOIN kelas k ON j.kelas_id = k.id
    JOIN guru gu ON j.guru_id = gu.id
    WHERE s.id = ?
");
$stmt_sesi->execute([$sesi_id]);
$sesi = $stmt_sesi->fetch();

if (!$sesi) {
    die("Sesi tidak ditemukan.");
}

// 2. Get All Students in Class & Their Attendance Status
// LEFT JOIN presensi_mapel to see if they attended
$stmt_siswa = $pdo->prepare("
    SELECT s.nama_lengkap, s.nis, p.waktu_hadir, p.status
    FROM siswa s
    LEFT JOIN presensi_mapel p ON s.id = p.siswa_id AND p.sesi_id = ?
    WHERE s.kelas_id = ?
    ORDER BY s.nama_lengkap ASC
");
$stmt_siswa->execute([$sesi_id, $sesi['kelas_id']]);
$siswa_list = $stmt_siswa->fetchAll();

// Count Stats
$total_siswa = count($siswa_list);
$hadir_count = 0;
$alpha_count = 0;
$izin_count = 0;

foreach ($siswa_list as $sw) {
    if ($sw['status'] == 'Hadir') $hadir_count++;
    if ($sw['status'] == 'Alpha') $alpha_count++;
    if ($sw['status'] == 'Izin' || $sw['status'] == 'Sakit') $izin_count++;
}

$belum_absen = $total_siswa - ($hadir_count + $alpha_count + $izin_count);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Absensi - <?= htmlspecialchars($sesi['nama_kelas']) ?></title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Guru Panel</h2>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="fas fa-arrow-left"></i> <span>Kembali</span></a>
        </div>
    </div>

    <div class="main-content">
        <header style="margin-bottom: 2rem;">
            <h2>Rekap Absensi: <?= htmlspecialchars($sesi['nama_kelas']) ?></h2>
            <p class="subtitle">
                <?= htmlspecialchars($sesi['mata_pelajaran']) ?> |
                <?= date('d M Y', strtotime($sesi['tanggal'])) ?> |
                <?= $sesi['is_open'] ? '<span style="color:green; font-weight:bold;">Sesi Aktif</span>' : '<span style="color:red; font-weight:bold;">Sesi Ditutup</span>' ?>
            </p>
        </header>
        <h3 style="text-align: center  ;">Total Siswa</h3>
        <div class="stat-value" style="text-align: center; margin-bottom: 1rem; font-size: 2rem; font-weight: bold; color: var(--accent-color); "><?= $total_siswa ?></div>
        <div class="card-flex">
            <div class="card absensi-card">
                <h3>Hadir</h3>
                <div class="stat-value" style="color: #2e7d32;"><?= $hadir_count ?></div>
            </div>
            <div class="card absensi-card">
                <h3>Alpha</h3>
                <div class="stat-value" style="color: #c62828;"><?= $alpha_count ?></div>
            </div>
            <div class="card absensi-card">
                <h3>Belum Absen</h3>
                <div class="stat-value" style="color: #f9a825;"><?= $belum_absen ?></div>
            </div>
        </div>

        <div class="card">
            <h3>Daftar Kehadiran Siswa</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Waktu Absen</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($siswa_list as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nis']) ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td>
                                    <?php if ($row['waktu_hadir']): ?>
                                        <?= date('H:i:s', strtotime($row['waktu_hadir'])) ?>
                                    <?php else: ?>
                                        <span style="color: #999;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $label = 'Belum Absen';
                                    $badge_cls = 'warning'; // default for belum absen

                                    if ($row['status']) {
                                        $label = $row['status'];
                                        if ($label == 'Hadir') $badge_cls = 'success';
                                        elseif ($label == 'Alpha') $badge_cls = 'danger';
                                        elseif ($label == 'Izin' || $label == 'Sakit') $badge_cls = 'info';
                                    }
                                    ?>
                                    <span class="badge <?= $badge_cls ?>"><?= $label ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>