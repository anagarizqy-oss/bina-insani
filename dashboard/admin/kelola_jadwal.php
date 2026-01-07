<?php
// dashboard/admin/kelola_jadwal.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['admin']);

$message = '';
if (isset($_SESSION['success_message'])) {
    $message = "<div class='alert success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $message = "<div class='alert error'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}

// Tambah Jadwal
if (isset($_POST['tambah_jadwal'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Token CSRF tidak valid.</div>";
    } else {
        $kelas_id = (int)$_POST['kelas_id'];
        $mapel = $_POST['mapel']; // Not stored directly, used to get guru
        $guru_id = (int)$_POST['guru_id'];
        $hari = $_POST['hari'];
        $jam_mulai = $_POST['jam_mulai'];
        $jam_selesai = $_POST['jam_selesai'];

        if ($kelas_id && $guru_id && $hari && $jam_mulai && $jam_selesai) {

            // Validasi: Cek apakah guru sudah mengajar di jam yang sama pada hari yang sama
            // Logic Overlap: (StartA < EndB) AND (EndA > StartB)
            $check_overlap = $pdo->prepare("
                SELECT k.nama_kelas, j.jam_mulai, j.jam_selesai 
                FROM jadwal_pelajaran j
                JOIN kelas k ON j.kelas_id = k.id
                WHERE j.guru_id = ? 
                AND j.hari = ? 
                AND (j.jam_mulai < ? AND j.jam_selesai > ?)
            ");
            // Params: guru, hari, new_end, new_start
            $check_overlap->execute([$guru_id, $hari, $jam_selesai, $jam_mulai]);
            $conflict = $check_overlap->fetch();

            if ($conflict) {
                $conflict_time = date('H:i', strtotime($conflict['jam_mulai'])) . "-" . date('H:i', strtotime($conflict['jam_selesai']));
                $message = "<div class='alert error'>
                    Gagal! Guru ini sudah memiliki jadwal mengajar di kelas <strong>{$conflict['nama_kelas']}</strong> 
                    pada pukul <strong>{$conflict_time}</strong>.
                </div>";
            } else {
                try {
                    $stmt = $pdo->prepare("INSERT INTO jadwal_pelajaran (kelas_id, guru_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$kelas_id, $guru_id, $hari, $jam_mulai, $jam_selesai]);
                    $_SESSION['success_message'] = "Jadwal berhasil ditambahkan.";
                    header("Location: kelola_jadwal.php");
                    exit;
                } catch (PDOException $e) {
                    $message = "<div class='alert error'>Gagal menambah jadwal.</div>";
                }
            }
        } else {
            $message = "<div class='alert error'>Semua field wajib diisi.</div>";
        }
    }
}

// Hapus Jadwal
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    try {
        $stmt = $pdo->prepare("DELETE FROM jadwal_pelajaran WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success_message'] = "Jadwal berhasil dihapus.";
        header("Location: kelola_jadwal.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Gagal menghapus jadwal.";
        header("Location: kelola_jadwal.php");
        exit;
    }
}

// Data Pendukung
$kelas_list = $pdo->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
$mapel_list = $pdo->query("SELECT DISTINCT mata_pelajaran FROM guru ORDER BY mata_pelajaran ASC");
// Fetch Jadwal with relationships
$jadwal_list = $pdo->query("
    SELECT j.*, k.nama_kelas, g.nama_lengkap, g.mata_pelajaran 
    FROM jadwal_pelajaran j
    JOIN kelas k ON j.kelas_id = k.id
    JOIN guru g ON j.guru_id = g.id
    ORDER BY k.nama_kelas ASC, FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai ASC
");

$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal - Admin SMA BINA INSANI</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Admin Panel</h2>
        </div>

        <div class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href="data_siswa.php" class="menu-item"><i class="fas fa-user-graduate"></i> <span>Data Siswa</span></a>
            <a href="data_guru.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span></a>
            <a href="kelola_kelas.php" class="menu-item"><i class="fas fa-school"></i> <span>Kelola Kelas</span></a>
            <a href="kelola_jadwal.php" class="menu-item active"><i class="fas fa-calendar-alt"></i> <span>Kelola Jadwal</span></a>
            <a href="kelola_berita.php" class="menu-item"><i class="fas fa-newspaper"></i> <span>Kelola Berita</span></a>
            <a href="kelola_ekstrakurikuler.php" class="menu-item"><i class="fas fa-futbol"></i> <span>Ekstrakurikuler</span></a>
            <a href="masukan.php" class="menu-item"><i class="fas fa-envelope-open-text"></i> <span>Masukan & Saran</span></a>
            <a href="kelola_galeri.php" class="menu-item"><i class="fas fa-images"></i> <span>Kelola Galeri</span></a>
        </div>

        <div class="sidebar-footer">
            <a href="../../logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header style="margin-bottom: 2rem;">
            <h2>Kelola Jadwal Pelajaran</h2>
            <p class="subtitle">Atur mata pelajaran dan guru pengampu untuk setiap kelas</p>
        </header>

        <div class="card" style="margin-bottom: 2rem;">
            <h3>Tambah Jadwal Baru</h3>
            <?= $message ?>
            <form method="POST" style="margin-top: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div style="grid-column: span 2;">
                    <label>Kelas</label>
                    <select name="kelas_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">-- Pilih Kelas --</option>
                        <?php while ($kls = $kelas_list->fetch()): ?>
                            <option value="<?= $kls['id'] ?>"><?= htmlspecialchars($kls['nama_kelas']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label>Mata Pelajaran</label>
                    <select id="mapel" name="mapel" required onchange="fetchTeachers()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">-- Pilih Mapel --</option>
                        <?php while ($mpl = $mapel_list->fetch()): ?>
                            <option value="<?= htmlspecialchars($mpl['mata_pelajaran']) ?>"><?= htmlspecialchars($mpl['mata_pelajaran']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label>Guru Pengampu</label>
                    <select id="guru_id" name="guru_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">-- Pilih Mapel Terlebih Dahulu --</option>
                    </select>
                </div>

                <div>
                    <label>Hari</label>
                    <select name="hari" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div style="grid-column: span 2;">
                    <button type="submit" name="tambah_jadwal" style="background: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%;">
                        <i class="fas fa-plus"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Jadwal Pelajaran</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">Kelas</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">Hari</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Waktu</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">Mata Pelajaran</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">Guru</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($jadwal_list->rowCount() > 0): ?>
                            <?php while ($row = $jadwal_list->fetch()): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px;"><strong><?= htmlspecialchars($row['nama_kelas']) ?></strong></td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($row['hari']) ?></td>
                                    <td style="padding: 12px; text-align: center;">
                                        <?= date('H:i', strtotime($row['jam_mulai'])) ?> - <?= date('H:i', strtotime($row['jam_selesai'])) ?>
                                    </td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                    <td style="padding: 12px; text-align: center;">
                                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" style="color: #e53935; text-decoration: none;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px; color: #666;">Belum ada jadwal.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- AJAX Script -->
    <script>
        function fetchTeachers() {
            const mapel = document.getElementById('mapel').value;
            const guruSelect = document.getElementById('guru_id');
            guruSelect.innerHTML = '<option value="">Loading...</option>';

            if (mapel) {
                fetch(`function_admin/get_guru_by_mapel.php?mapel=${encodeURIComponent(mapel)}`)
                    .then(response => response.json())
                    .then(data => {
                        guruSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
                        data.forEach(guru => {
                            const option = document.createElement('option');
                            option.value = guru.id;
                            option.textContent = guru.nama_lengkap;
                            guruSelect.appendChild(option);
                        });
                        if (data.length === 0) {
                            guruSelect.innerHTML = '<option value="">Tidak ada guru untuk mapel ini</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        guruSelect.innerHTML = '<option value="">Error memuat data</option>';
                    });
            } else {
                guruSelect.innerHTML = '<option value="">-- Pilih Mapel Terlebih Dahulu --</option>';
            }
        }
    </script>

</body>

</html>