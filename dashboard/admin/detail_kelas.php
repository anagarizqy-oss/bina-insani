<?php
// dashboard/admin/detail_kelas.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['admin']);

if (!isset($_GET['id'])) {
    header("Location: kelola_kelas.php");
    exit;
}

$kelas_id = (int)$_GET['id'];
$message = '';
if (isset($_SESSION['success_message'])) {
    $message = "<div class='alert success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

// Update Wali Kelas
if (isset($_POST['update_wali'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Token CSRF tidak valid.</div>";
    } else {
        $wali_kelas_id = !empty($_POST['wali_kelas_id']) ? (int)$_POST['wali_kelas_id'] : null;

        // Cek jika guru sudah menjadi wali kelas di kelas lain
        if ($wali_kelas_id) {
            $check = $pdo->prepare("SELECT nama_kelas FROM kelas WHERE wali_kelas_id = ? AND id != ?");
            $check->execute([$wali_kelas_id, $kelas_id]);
            $existing = $check->fetch();

            if ($existing) {
                $message = "<div class='alert error'>Guru ini sudah menjadi wali kelas untuk <strong>" . htmlspecialchars($existing['nama_kelas']) . "</strong>.</div>";
                // Prevent update
                $wali_kelas_id = null; // Or handle differently, but here we just show error and don't execute update logic below if we wrap it.
                // Let's restructure to stop execution.
            } else {
                try {
                    $stmt = $pdo->prepare("UPDATE kelas SET wali_kelas_id = ? WHERE id = ?");
                    $stmt->execute([$wali_kelas_id, $kelas_id]);
                    $_SESSION['success_message'] = "Wali Kelas berhasil diperbarui.";
                    header("Location: detail_kelas.php?id=$kelas_id");
                    exit;
                } catch (PDOException $e) {
                    $message = "<div class='alert error'>Gagal memperbarui Wali Kelas.</div>";
                }
            }
        } else {
            // Unassigning (setting to null) is always allowed
            try {
                $stmt = $pdo->prepare("UPDATE kelas SET wali_kelas_id = NULL WHERE id = ?");
                $stmt->execute([$kelas_id]);
                $_SESSION['success_message'] = "Wali Kelas berhasil dihapus.";
                header("Location: detail_kelas.php?id=$kelas_id");
                exit;
            } catch (PDOException $e) {
                $message = "<div class='alert error'>Gagal menghapus Wali Kelas.</div>";
            }
        }
    }
}

// Get Data Kelas & Wali
$stmt = $pdo->prepare("
    SELECT k.*, g.nama_lengkap as nama_wali 
    FROM kelas k 
    LEFT JOIN guru g ON k.wali_kelas_id = g.id 
    WHERE k.id = ?
");
$stmt->execute([$kelas_id]);
$kelas = $stmt->fetch();

if (!$kelas) {
    echo "Kelas tidak ditemukan.";
    exit;
}

// Get All Teachers for Dropdown
$guru_list = $pdo->query("SELECT * FROM guru ORDER BY nama_lengkap ASC");

// Get Jadwal
$jadwal_q = $pdo->prepare("
    SELECT j.*, g.nama_lengkap, g.mata_pelajaran 
    FROM jadwal_pelajaran j
    JOIN guru g ON j.guru_id = g.id
    WHERE j.kelas_id = ?
    ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai ASC
");
$jadwal_q->execute([$kelas_id]);
$jadwal = $jadwal_q->fetchAll(PDO::FETCH_GROUP); // Group by Hari automatically? No, fetchAll(PDO::FETCH_GROUP) needs first column to contain group key if planned.
// Actually FETCH_GROUP groups by first column. Let's adjust query for that or process in PHP.
// Modified query for FETCH_GROUP:
$jadwal_q = $pdo->prepare("
    SELECT j.hari, j.*, g.nama_lengkap, g.mata_pelajaran 
    FROM jadwal_pelajaran j
    JOIN guru g ON j.guru_id = g.id
    WHERE j.kelas_id = ?
    ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai ASC
");
$jadwal_q->execute([$kelas_id]);
$jadwal_grouped = $jadwal_q->fetchAll(PDO::FETCH_GROUP);

$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?> - Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Admin Panel</h2>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href="data_siswa.php" class="menu-item"><i class="fas fa-user-graduate"></i> <span>Data Siswa</span></a>
            <a href="data_guru.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span></a>
            <a href="kelola_kelas.php" class="menu-item active"><i class="fas fa-school"></i> <span>Kelola Kelas</span></a>
            <a href="kelola_jadwal.php" class="menu-item"><i class="fas fa-calendar-alt"></i> <span>Kelola Jadwal</span></a>
            <a href="kelola_berita.php" class="menu-item"><i class="fas fa-newspaper"></i> <span>Kelola Berita</span></a>
            <a href="kelola_ekstrakurikuler.php" class="menu-item"><i class="fas fa-futbol"></i> <span>Ekstrakurikuler</span></a>
            <a href="masukan.php" class="menu-item"><i class="fas fa-envelope-open-text"></i> <span>Masukan & Saran</span></a>
            <a href="kelola_galeri.php" class="menu-item"><i class="fas fa-images"></i> <span>Kelola Galeri</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="../../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">
        <header style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2>Detail Kelas: <?= htmlspecialchars($kelas['nama_kelas']) ?></h2>
                <p class="subtitle">Manajemen Wali Kelas dan Jadwal Pelajaran</p>
            </div>
            <a href="kelola_kelas.php" style="background: #666; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">&larr; Kembali</a>
        </header>

        <?= $message ?>

        <!-- Wali Kelas Section -->
        <div class="card" style="margin-bottom: 2rem;">
            <h3><i class="fas fa-user-tie"></i> Wali Kelas</h3>
            <div style="margin-top: 1rem; background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 5px solid #2575fc;">
                <?php if ($kelas['nama_wali']): ?>
                    <p style="font-size: 1.1rem; margin-bottom: 5px;">Saat ini: <strong><?= htmlspecialchars($kelas['nama_wali']) ?></strong></p>
                <?php else: ?>
                    <p style="color: #666; margin-bottom: 5px;">Belum ada Wali Kelas.</p>
                <?php endif; ?>

                <form method="POST" style="margin-top: 15px; display: flex; gap: 10px; align-items: center;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <select name="wali_kelas_id" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-width: 250px;">
                        <option value="">-- Pilih Wali Kelas Baru --</option>
                        <?php while ($g = $guru_list->fetch()): ?>
                            <option value="<?= $g['id'] ?>" <?= ($kelas['wali_kelas_id'] == $g['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['nama_lengkap']) ?> (<?= htmlspecialchars($g['mata_pelajaran']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" name="update_wali" style="background: #2575fc; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <!-- Jadwal Section -->
        <div class="card">
            <h3><i class="fas fa-calendar-week"></i> Jadwal Pelajaran</h3>
            <div style="margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($days as $day): ?>
                    <?php if (isset($jadwal_grouped[$day])): ?>
                        <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                            <div style="background: #2575fc; color: white; padding: 10px; font-weight: bold; text-align: center;">
                                <?= $day ?>
                            </div>
                            <div style="padding: 10px;">
                                <table style="width: 100%; font-size: 0.9rem;">
                                    <?php foreach ($jadwal_grouped[$day] as $j): ?>
                                        <tr style="border-bottom: 1px solid #eee;">
                                            <td style="padding: 5px; font-weight: bold; width: 90px;">
                                                <?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?>
                                            </td>
                                            <td style="padding: 5px;">
                                                <div style="font-weight: bold; color: #333;"><?= htmlspecialchars($j['mata_pelajaran']) ?></div>
                                                <div style="color: #666; font-size: 0.85rem;"><?= htmlspecialchars($j['nama_lengkap']) ?></div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (empty($jadwal_grouped)): ?>
                    <div style="grid-column: span 3; text-align: center; color: #666; padding: 20px;">
                        Belum ada jadwal pelajaran untuk kelas ini. <br>
                        <a href="kelola_jadwal.php" style="color: #2575fc;">Kelola Jadwal di sini</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>

</html>