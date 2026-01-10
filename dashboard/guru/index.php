<?php
// dashboard/guru/index.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

$user_id = $_SESSION['user_id'];

// Get Guru ID
$stmt = $pdo->prepare("SELECT id, nama_lengkap FROM guru WHERE user_id = ?");
$stmt->execute([$user_id]);
$guru = $stmt->fetch();
$guru_id = $guru['id'];

// Set Timezone to Indonesia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Get Hari Ini (Indonesian)
$days = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$hari_ini = $days[date('l')];
$tanggal_ini = date('Y-m-d');

// Handle "Buka Absensi" Action
if (isset($_POST['buka_absen'])) {
    $jadwal_id = (int)$_POST['jadwal_id'];

    // Check if checks already exist specifically for today?
    $stmt_check = $pdo->prepare("SELECT id FROM absensi_sesi WHERE jadwal_id = ? AND tanggal = ?");
    $stmt_check->execute([$jadwal_id, $tanggal_ini]);

    if (!$stmt_check->fetch()) {
        $stmt_ins = $pdo->prepare("INSERT INTO absensi_sesi (jadwal_id, tanggal, waktu_buka) VALUES (?, ?, NOW())");
        $stmt_ins->execute([$jadwal_id, $tanggal_ini]);
        $_SESSION['success_message'] = "Absensi berhasil dibuka.";
    }
    header("Location: index.php");
    exit;
}

// Handle "Tutup Absensi" Action
if (isset($_POST['tutup_absen'])) {
    $sesi_id = (int)$_POST['sesi_id'];

    // Auto-Alpha Logic: Mark students who haven't attended as 'Alpha'
    // 1. Get Class Info
    $stmt_info = $pdo->prepare("
        SELECT j.kelas_id 
        FROM absensi_sesi s
        JOIN jadwal_pelajaran j ON s.jadwal_id = j.id
        WHERE s.id = ?
    ");
    $stmt_info->execute([$sesi_id]);
    $session_info = $stmt_info->fetch();

    if ($session_info) {
        $kelas_id = $session_info['kelas_id'];

        // 2. Get All Students in Class
        $stmt_all_siswa = $pdo->prepare("SELECT id FROM siswa WHERE kelas_id = ?");
        $stmt_all_siswa->execute([$kelas_id]);
        $all_siswa = $stmt_all_siswa->fetchAll(PDO::FETCH_COLUMN);

        // 3. Get Already Present Students
        $stmt_present = $pdo->prepare("SELECT siswa_id FROM presensi_mapel WHERE sesi_id = ?");
        $stmt_present->execute([$sesi_id]);
        $present_siswa = $stmt_present->fetchAll(PDO::FETCH_COLUMN);

        // 4. Find Missing (Alpha)
        $missing_siswa = array_diff($all_siswa, $present_siswa);

        // 5. Insert Alpha
        if (!empty($missing_siswa)) {
            $stmt_ins_alpha = $pdo->prepare("INSERT INTO presensi_mapel (sesi_id, siswa_id, waktu_hadir, status) VALUES (?, ?, NOW(), 'Alpha')");
            foreach ($missing_siswa as $s_id) {
                $stmt_ins_alpha->execute([$sesi_id, $s_id]);
            }
        }
    }

    $stmt_upd = $pdo->prepare("UPDATE absensi_sesi SET is_open = 0, waktu_tutup = NOW() WHERE id = ?");
    $stmt_upd->execute([$sesi_id]);
    $_SESSION['success_message'] = "Absensi ditutup. Siswa yang tidak hadir ditandai Alpha.";
    header("Location: index.php");
    exit;
}

// Fetch Jadwal Semua Hari
// Left Join to absensi_sesi to see if open today (limited to current date join)
$query = "
    SELECT j.*, k.nama_kelas, g.mata_pelajaran, s.id as sesi_id, s.is_open, s.waktu_buka, s.waktu_tutup
    FROM jadwal_pelajaran j
    JOIN kelas k ON j.kelas_id = k.id
    JOIN guru g ON j.guru_id = g.id
    LEFT JOIN absensi_sesi s ON j.id = s.jadwal_id AND s.tanggal = ?
    WHERE j.guru_id = ?
    ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai ASC
";
$stmt_jadwal = $pdo->prepare($query);
$stmt_jadwal->execute([$tanggal_ini, $guru_id]);
$all_jadwal = $stmt_jadwal->fetchAll();

// Group by Hari
$jadwal_by_hari = [];
foreach ($all_jadwal as $row) {
    $jadwal_by_hari[$row['hari']][] = $row;
}
$days_order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/css/admin.css"> <!-- Reusing admin css for layout -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles for Guru Dashboard */
        .timeline-card {
            border-left: 4px solid #2575fc;
            margin-bottom: 20px;
        }

        .status-open {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-closed {
            background: #e0e0e0;
            color: #666;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status-waiting {
            background: #fff3e0;
            color: #ef6c00;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }

        .disabled-action {
            color: #999;
            font-size: 0.9rem;
            font-style: italic;
        }
    </style>
</head>

<body>

    <!-- Simple Sidebar for Guru -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Guru Panel</h2>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="menu-item active"><i class="fas fa-home"></i> <span>Dashboard & Jadwal</span></a>
            <a href="input_nilai.php" class="menu-item"><i class="fas fa-edit"></i> <span>Input Nilai</span></a>
            <a href="../../logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">
        <header style="margin-bottom: 2rem;">
            <h2>Selamat Datang, <?= htmlspecialchars($guru['nama_lengkap']) ?></h2>
            <p class="subtitle">Hari ini: <strong><?= $hari_ini ?>, <?= date('d M Y') ?></strong> (<?= date('H:i') ?>)</p>
        </header>

        <h3 class="section-title">Jadwal Mengajar Anda</h3>

        <?php if (count($jadwal_by_hari) > 0): ?>
            <?php foreach ($days_order as $day_name): ?>
                <?php if (isset($jadwal_by_hari[$day_name])): ?>
                    <div style="margin-bottom: 2rem;">
                        <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; color: #444;">
                            <?= $day_name ?>
                            <?php if ($day_name == $hari_ini): ?>
                                <span style="background: #2575fc; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; vertical-align: middle; margin-left: 10px;">HARI INI</span>
                            <?php endif; ?>
                        </h3>

                        <div style="display: grid; gap: 20px;">
                            <?php foreach ($jadwal_by_hari[$day_name] as $row): ?>
                                <?php
                                // Logic Check Time (Using server time Asia/Jakarta)
                                $current_time = date('H:i:s');
                                $start_check = date('H:i:s', strtotime($row['jam_mulai']));
                                $end_check = date('H:i:s', strtotime($row['jam_selesai']));

                                // Buffer: 30 minutes before start
                                $start_buffer = date('H:i:s', strtotime($start_check) - 1800);

                                $can_open = false;
                                $time_message = "";

                                if ($day_name == $hari_ini) {
                                    if ($current_time < $start_buffer) {
                                        $time_message = "Belum waktunya (" . date('H:i', strtotime($start_check)) . ")";
                                    } elseif ($current_time > $end_check) {
                                        $time_message = "Sesi berakhir (" . date('H:i', strtotime($end_check)) . ")";
                                    } else {
                                        $can_open = true;
                                    }

                                    // Override
                                    if ($row['sesi_id']) {
                                        $can_open = true; // irrelevant but kept logic
                                    }
                                } else {
                                    $time_message = "Bukan jadwal hari ini";
                                }
                                ?>
                                <div class="card timeline-card" style="<?= ($day_name != $hari_ini) ? 'border-left-color: #ddd; opacity: 0.8;' : '' ?>">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px;">
                                        <div>
                                            <h3 style="margin: 0; color: #333;"><?= htmlspecialchars($row['nama_kelas']) ?></h3>
                                            <div style="color: #666; margin-top: 5px;">
                                                <i class="far fa-clock"></i> <?= date('H:i', strtotime($row['jam_mulai'])) ?> - <?= date('H:i', strtotime($row['jam_selesai'])) ?>
                                            </div>
                                            <div style="margin-top: 5px; font-weight: 500;">
                                                Mata Pelajaran: <?= htmlspecialchars($row['mata_pelajaran'] ?? '-') ?>
                                            </div>
                                        </div>

                                        <div style="text-align: right;">
                                            <?php if ($row['sesi_id']): ?>
                                                <!-- Sesi Sudah Dibuat -->
                                                <?php if ($row['is_open']): ?>
                                                    <div style="margin-bottom: 10px;"><span class="status-open"><i class="fas fa-circle-notch fa-spin"></i> Absensi Dibuka</span></div>
                                                    <div style="display: flex; gap: 5px; justify-content: flex-end;">
                                                        <a href="detail_absensi.php?id=<?= $row['sesi_id'] ?>" class="btn-import" style="text-decoration: none; padding: 8px 12px; font-size: 0.9rem;">Lihat Data</a>
                                                        <form method="POST" onsubmit="return confirm('Tutup absensi? Siswa tidak akan bisa absen lagi.')">
                                                            <input type="hidden" name="sesi_id" value="<?= $row['sesi_id'] ?>">
                                                            <button type="submit" name="tutup_absen" style="background: #e53935; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Tutup</button>
                                                        </form>
                                                    </div>
                                                <?php else: ?>
                                                    <div style="margin-bottom: 10px;"><span class="status-closed">Absensi Ditutup</span></div>
                                                    <a href="detail_absensi.php?id=<?= $row['sesi_id'] ?>" class="btn-import" style="text-decoration: none; padding: 8px 12px; font-size: 0.9rem;">Lihat Rekap</a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- Belum Ada Sesi -->
                                                <?php if ($can_open): ?>
                                                    <div style="margin-bottom: 10px;"><span class="status-waiting">Belum Dimulai</span></div>
                                                    <form method="POST">
                                                        <input type="hidden" name="jadwal_id" value="<?= $row['id'] ?>">
                                                        <button type="submit" name="buka_absen" style="background: #2575fc; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: 500;">
                                                            <i class="fas fa-bullhorn"></i> Buka Absensi
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <div style="margin-bottom: 10px; visibility: hidden;">placeholder</div>
                                                    <div class="disabled-action">
                                                        <?= $time_message ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 3rem; color: #666;">
                <i class="fas fa-calendar-day" style="font-size: 2rem; margin-bottom: 1rem; color: #ddd;"></i>
                <p>Belum ada jadwal mengajar yang ditentukan.</p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>