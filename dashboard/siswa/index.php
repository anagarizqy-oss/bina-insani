<?php
// dashboard/siswa/index.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];

// Get Student ID & Class Info
$stmt = $pdo->prepare("
    SELECT s.*, k.nama_kelas, k.id as kelas_id_real, g.nama_lengkap as nama_wali
    FROM siswa s 
    LEFT JOIN kelas k ON s.kelas_id = k.id
    LEFT JOIN guru g ON k.wali_kelas_id = g.id
    WHERE s.user_id = ?
");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$siswa_id = $siswa['id'];
$kelas_id = $siswa['kelas_id_real'];

// Quick Stats: Total Kehadiran
$stmt_hadir = $pdo->prepare("SELECT COUNT(*) FROM presensi_mapel WHERE siswa_id = ? AND status = 'Hadir'");
$stmt_hadir->execute([$siswa_id]);
$total_hadir = $stmt_hadir->fetchColumn();

// Quick Stats: Total Alpha
$stmt_alpha = $pdo->prepare("SELECT COUNT(*) FROM presensi_mapel WHERE siswa_id = ? AND status = 'Alpha'");
$stmt_alpha->execute([$siswa_id]);
$total_alpha = $stmt_alpha->fetchColumn();

// Quick Stats: SPP Terakhir
$stmt_spp = $pdo->prepare("SELECT bulan, status FROM spp WHERE siswa_id = ? ORDER BY id DESC LIMIT 1");
$stmt_spp->execute([$siswa_id]);
$last_spp = $stmt_spp->fetch();

// Get Jadwal
$jadwal_grouped = [];
if ($kelas_id) {
    $jadwal_q = $pdo->prepare("
        SELECT j.hari, j.*, g.nama_lengkap, g.mata_pelajaran 
        FROM jadwal_pelajaran j
        JOIN guru g ON j.guru_id = g.id
        WHERE j.kelas_id = ?
        ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai ASC
    ");
    $jadwal_q->execute([$kelas_id]);
    $jadwal_grouped = $jadwal_q->fetchAll(PDO::FETCH_GROUP);
}

$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

// Handle "Isi Absensi" Action
if (isset($_POST['absen_hadir'])) {
    $sesi_id = (int)$_POST['sesi_id'];

    // Validate session is open and belongs to student's class
    $stmt_val = $pdo->prepare("
        SELECT s.id 
        FROM absensi_sesi s
        JOIN jadwal_pelajaran j ON s.jadwal_id = j.id
        WHERE s.id = ? AND j.kelas_id = ? AND s.is_open = 1
    ");
    $stmt_val->execute([$sesi_id, $kelas_id]);

    if ($stmt_val->fetch()) {
        // Check if already present
        $stmt_dup = $pdo->prepare("SELECT id FROM presensi_mapel WHERE sesi_id = ? AND siswa_id = ?");
        $stmt_dup->execute([$sesi_id, $siswa_id]);

        if (!$stmt_dup->fetch()) {
            $stmt_ins = $pdo->prepare("INSERT INTO presensi_mapel (sesi_id, siswa_id, waktu_hadir, status) VALUES (?, ?, NOW(), 'Hadir')");
            $stmt_ins->execute([$sesi_id, $siswa_id]);
            $success_msg = "Berhasil melakukan absensi.";
        } else {
            $error_msg = "Anda sudah melakukan absensi.";
        }
    }
}

// Get Active Attendance Sessions for This Student's Class
$active_sessions = [];
if ($kelas_id) {
    $stmt_active = $pdo->prepare("
        SELECT s.id as sesi_id, j.jam_mulai, j.jam_selesai, g.nama_lengkap, g.mata_pelajaran
        FROM absensi_sesi s
        JOIN jadwal_pelajaran j ON s.jadwal_id = j.id
        JOIN guru g ON j.guru_id = g.id
        WHERE j.kelas_id = ? 
        AND s.is_open = 1 
        AND s.tanggal = CURDATE()
        AND s.id NOT IN (SELECT sesi_id FROM presensi_mapel WHERE siswa_id = ?)
    ");
    $stmt_active->execute([$kelas_id, $siswa_id]);
    $active_sessions = $stmt_active->fetchAll();
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            <span>Selamat Datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></span>
        </div>
    </div>

    <div class="page-content">
        <?php if (isset($success_msg)): ?>
            <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; border: 1px solid #c8e6c9;">
                <i class="fas fa-check-circle"></i> <?= $success_msg ?>
            </div>
        <?php endif; ?>

        <!-- Active Attendance Notification -->
        <?php if (count($active_sessions) > 0): ?>
            <div class="card" style="background: #fff3e0; border-left: 5px solid #ff9800; margin-bottom: 2rem;">
                <h3 style="color: #ef6c00; margin-bottom: 10px;"><i class="fas fa-bell"></i> Absensi Kelas Dibuka!</h3>
                <p>Guru telah membuka absensi. Silakan konfirmasi kehadiran Anda untuk mata pelajaran berikut:</p>

                <div style="display: grid; gap: 10px; margin-top: 15px;">
                    <?php foreach ($active_sessions as $sesi): ?>
                        <div style="background: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div>
                                <strong style="font-size: 1.1rem;"><?= htmlspecialchars($sesi['mata_pelajaran']) ?></strong>
                                <div style="color: #666; font-size: 0.9rem; margin-top: 5px;">
                                    <i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($sesi['nama_lengkap']) ?>
                                </div>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="sesi_id" value="<?= $sesi['sesi_id'] ?>">
                                <button type="submit" name="absen_hadir" style="background: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                                    <i class="fas fa-hand-paper"></i> SAYA HADIR
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <h2 class="section-title">Dashboard Overview</h2>

        <div class="card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h3 style="margin-bottom: 5px;">Profil Siswa</h3>
                    <p><strong>NIS:</strong> <?= htmlspecialchars($siswa['nis']) ?></p>
                    <p><strong>Nama:</strong> <?= htmlspecialchars($siswa['nama_lengkap']) ?></p>
                    <p><strong>Kelas:</strong> <?= htmlspecialchars($siswa['nama_kelas'] ?? 'Belum ditentukan') ?></p>
                </div>
                <div>
                    <h3 style="margin-bottom: 5px;">Wali Kelas</h3>
                    <?php if (!empty($siswa['nama_wali'])): ?>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: #e3f2fd; color: #1565c0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <strong style="font-size: 1.1rem;"><?= htmlspecialchars($siswa['nama_wali']) ?></strong>
                            </div>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; font-style: italic;">Belum ditentukan</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3><i class="fas fa-check-circle" style="color: var(--success-color);"></i> Kehadiran</h3>
                <p>Total Hadir</p>
                <div class="stat-value"><?= $total_hadir ?></div>
                <small>Hari</small>
            </div>

            <div class="card">
                <h3><i class="fas fa-times-circle" style="color: var(--danger-color);"></i> Absen (Alpha)</h3>
                <p>Total Alpha</p>
                <div class="stat-value" style="color: var(--danger-color);"><?= $total_alpha ?></div>
                <small>Hari</small>
            </div>

            <div class="card">
                <h3><i class="fas fa-wallet" style="color: var(--warning-color);"></i> Status SPP Terakhir</h3>
                <?php if ($last_spp): ?>
                    <p>Bulan: <?= htmlspecialchars($last_spp['bulan']) ?></p>
                    <div class="badge <?= $last_spp['status'] == 'Lunas' ? 'success' : 'danger' ?>" style="display:inline-block; margin-top:5px;">
                        <?= $last_spp['status'] ?>
                    </div>
                <?php else: ?>
                    <p>Belum ada data SPP</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Jadwal Section -->
        <h3 class="section-title" style="margin-top: 30px;">Jadwal Pelajaran</h3>

        <?php if ($kelas_id): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($days as $day): ?>
                    <?php if (isset($jadwal_grouped[$day])): ?>
                        <div class="card" style="padding: 0; overflow: hidden;">
                            <div style="background: #2575fc; color: white; padding: 10px 15px; font-weight: bold;">
                                <?= $day ?>
                            </div>
                            <div style="padding: 15px;">
                                <table style="width: 100%; font-size: 0.9rem;">
                                    <?php foreach ($jadwal_grouped[$day] as $j): ?>
                                        <tr style="border-bottom: 1px solid #eee;">
                                            <td style="padding: 8px 0; width: 35%; font-weight: 500; color: #555;">
                                                <?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?>
                                            </td>
                                            <td style="padding: 8px 0;">
                                                <div style="font-weight: bold; color: #333; margin-bottom: 2px;"><?= htmlspecialchars($j['mata_pelajaran']) ?></div>
                                                <div style="font-size: 0.85rem; color: #666; display: flex; align-items: center; gap: 5px;">
                                                    <i class="fas fa-chalkboard-teacher" style="font-size: 0.8em;"></i> <?= htmlspecialchars($j['nama_lengkap']) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (empty($jadwal_grouped)): ?>
                    <div class="card" style="grid-column: 1 / -1; text-align: center; color: #666;">
                        Belum ada jadwal pelajaran untuk kelas ini.
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; color: #666;">
                Anda belum terdaftar dalam kelas apapun. Hubungi admin.
            </div>
        <?php endif; ?>

        <br><br>
    </div>
</div>

</body>

</html>