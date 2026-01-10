<?php
// dashboard/guru/input_nilai.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

$user_id = $_SESSION['user_id'];

// 1. Get Teacher Info & Subject
$stmt_guru = $pdo->prepare("SELECT id, nama_lengkap, mata_pelajaran FROM guru WHERE user_id = ?");
$stmt_guru->execute([$user_id]);
$guru = $stmt_guru->fetch();
$guru_id = $guru['id'];
$mata_pelajaran = $guru['mata_pelajaran'];

// 2. Get Classes Taught by this Teacher
$stmt_kelas = $pdo->prepare("
    SELECT DISTINCT k.id, k.nama_kelas 
    FROM jadwal_pelajaran j
    JOIN kelas k ON j.kelas_id = k.id
    WHERE j.guru_id = ?
    ORDER BY k.nama_kelas
");
$stmt_kelas->execute([$guru_id]);
$daftar_kelas = $stmt_kelas->fetchAll();

// 3. Handle Class Selection
$selected_kelas_id = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : null;
$siswa_list = [];

if ($selected_kelas_id) {
    // Check if teacher actually teaches this class
    $valid_class = false;
    foreach ($daftar_kelas as $kls) {
        if ($kls['id'] == $selected_kelas_id) $valid_class = true;
    }

    if ($valid_class) {
        // Fetch Students and Their Grades for this Subject
        $query_siswa = "
            SELECT s.id, s.nama_lengkap, s.nis,
                   n.uh1, n.uh2, n.uh3, n.uh4,
                   n.tugas1, n.tugas2, n.tugas3, n.tugas4,
                   n.uts, n.uas
            FROM siswa s
            LEFT JOIN nilai n ON s.id = n.siswa_id AND n.mapel = ?
            WHERE s.kelas_id = ?
            ORDER BY s.nama_lengkap ASC
        ";
        $stmt_list = $pdo->prepare($query_siswa);
        $stmt_list->execute([$mata_pelajaran, $selected_kelas_id]);
        $siswa_list = $stmt_list->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - <?= htmlspecialchars($mata_pelajaran) ?></title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grade-input {
            width: 50px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .grade-input:focus {
            border-color: #2575fc;
            outline: none;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        th {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo-navbar.png" alt="Logo" class="sidebar-logo">
            <h2>Guru Panel</h2>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href="input_nilai.php" class="menu-item active"><i class="fas fa-edit"></i> <span>Input Nilai</span></a>
            <!-- Add other links if needed -->
            <a href="../../logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">
        <header style="margin-bottom: 2rem;">
            <h2>Input Nilai: <?= htmlspecialchars($mata_pelajaran) ?></h2>
            <p class="subtitle">Kelola nilai siswa untuk mata pelajaran ini.</p>
        </header>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert success"><?= $_SESSION['success_message'];
                                        unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
                <label style="font-weight: bold;">Pilih Kelas:</label>
                <select name="kelas_id" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($daftar_kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $selected_kelas_id == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if ($selected_kelas_id && !empty($siswa_list)): ?>
                <form action="simpan_nilai.php" method="POST">
                    <input type="hidden" name="kelas_id" value="<?= $selected_kelas_id ?>">
                    <input type="hidden" name="mapel" value="<?= htmlspecialchars($mata_pelajaran) ?>">

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2" style="text-align: left; min-width: 200px;">Nama Siswa</th>
                                    <th colspan="4" style="text-align: center;">Ulangan Harian</th>
                                    <th colspan="4" style="text-align: center;">Tugas</th>
                                    <th rowspan="2" style="text-align: center;">UTS</th>
                                    <th rowspan="2" style="text-align: center;">UAS</th>
                                </tr>
                                <tr>
                                    <th>1</th>
                                    <th>2</th>
                                    <th>3</th>
                                    <th>4</th>
                                    <th>1</th>
                                    <th>2</th>
                                    <th>3</th>
                                    <th>4</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($siswa_list as $s): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($s['nama_lengkap']) ?></strong><br>
                                            <small><?= htmlspecialchars($s['nis']) ?></small>
                                            <input type="hidden" name="siswa_id[]" value="<?= $s['id'] ?>">
                                        </td>
                                        <!-- UH -->
                                        <td><input type="number" step="0.01" class="grade-input" name="uh1[<?= $s['id'] ?>]" value="<?= $s['uh1'] ?>"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="uh2[<?= $s['id'] ?>]" value="<?= $s['uh2'] ?>"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="uh3[<?= $s['id'] ?>]" value="<?= $s['uh3'] ?>"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="uh4[<?= $s['id'] ?>]" value="<?= $s['uh4'] ?>"></td>
                                        <!-- Tugas -->
                                        <td><input type="number" step="0.01" class="grade-input" name="tugas1[<?= $s['id'] ?>]" value="<?= $s['tugas1'] ?>"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="tugas2[<?= $s['id'] ?>]" value="<?= $s['tugas2'] ?>"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="tugas3[<?= $s['id'] ?>]" value="<?= $s['tugas3'] ?>"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="tugas4[<?= $s['id'] ?>]" value="<?= $s['tugas4'] ?>"></td>
                                        <!-- Exams -->
                                        <td><input type="number" step="0.01" class="grade-input" name="uts[<?= $s['id'] ?>]" value="<?= $s['uts'] ?>" style="background: #fff3e0;"></td>
                                        <td><input type="number" step="0.01" class="grade-input" name="uas[<?= $s['id'] ?>]" value="<?= $s['uas'] ?>" style="background: #e8f5e9;"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 20px; text-align: right;">
                        <button type="submit" class="btn-add" style="background: #2575fc; color: white; padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem;">
                            <i class="fas fa-save"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            <?php elseif ($selected_kelas_id): ?>
                <div class="alert warning">Belum ada siswa di kelas ini.</div>
            <?php else: ?>
                <p style="color: #666;">Silakan pilih kelas terlebih dahulu untuk menginput nilai.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>