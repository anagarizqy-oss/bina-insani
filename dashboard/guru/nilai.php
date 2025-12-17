<?php
// dashboard/guru/nilai.php
include '../../includes/auth.php';
include '../../includes/csrf.php';
include '../../config/db.php';
must_be(['guru']);

$message = '';

// Ambil daftar siswa
$siswa_list = $pdo->query("SELECT s.id, u.nama_lengkap FROM siswa s JOIN users u ON s.user_id = u.id ORDER BY u.nama_lengkap");

// Simpan nilai
if ($_POST && isset($_POST['simpan_nilai'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Permintaan tidak valid.</div>";
    } else {
        $siswa_id = (int)$_POST['siswa_id'];
        $mapel = clean($_POST['mapel']);
        $nilai = (float)$_POST['nilai'];
        $semester = $_POST['semester'];

        if ($siswa_id > 0 && !empty($mapel) && $nilai >= 0 && $nilai <= 100 && in_array($semester, ['Ganjil', 'Genap'])) {
            // Cek apakah nilai untuk mapel & semester ini sudah ada → update
            $cek = $pdo->prepare("SELECT id FROM nilai WHERE siswa_id = ? AND mapel = ? AND semester = ?");
            $cek->execute([$siswa_id, $mapel, $semester]);
            if ($cek->rowCount() > 0) {
                // Update
                $stmt = $pdo->prepare("UPDATE nilai SET nilai = ? WHERE siswa_id = ? AND mapel = ? AND semester = ?");
                $stmt->execute([$nilai, $siswa_id, $mapel, $semester]);
                $message = "<div class='alert success'>Nilai berhasil diperbarui!</div>";
            } else {
                // Insert baru
                $stmt = $pdo->prepare("INSERT INTO nilai (siswa_id, mapel, nilai, semester) VALUES (?, ?, ?, ?)");
                $stmt->execute([$siswa_id, $mapel, $nilai, $semester]);
                $message = "<div class='alert success'>Nilai berhasil disimpan!</div>";
            }
        } else {
            $message = "<div class='alert error'>Data tidak valid. Pastikan nilai antara 0–100.</div>";
        }
    }
}

$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - Guru</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <h1>SMA BINA INSANI</h1>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="nilai.php" style="border-bottom: 2px solid white;">Nilai</a>
            <a href="presensi.php">Presensi</a>
            <?php
            $is_wali = $pdo->prepare("SELECT is_wali_kelas FROM guru WHERE user_id = ?");
            $is_wali->execute([$_SESSION['user_id']]);
            if ($is_wali->fetchColumn()): ?>
                <a href="spp.php">SPP</a>
            <?php endif; ?>
            <a href="../../logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Input / Perbarui Nilai Siswa</h2>

        <div class="card">
            <?= $message ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <label>Pilih Siswa</label>
                <select name="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php while ($siswa = $siswa_list->fetch()): ?>
                        <option value="<?= $siswa['id'] ?>"><?= htmlspecialchars($siswa['nama_lengkap']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label>Mata Pelajaran</label>
                <input type="text" name="mapel" placeholder="Contoh: Matematika, Bahasa Indonesia" required>

                <label>Nilai (0–100)</label>
                <input type="number" name="nilai" min="0" max="100" step="0.1" placeholder="Contoh: 85.5" required>

                <label>Semester</label>
                <select name="semester" required>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>

                <button type="submit" name="simpan_nilai">Simpan Nilai</button>
            </form>
        </div>

        <!-- Tampilkan nilai yang sudah diinput -->
        <div class="card">
            <h3>Nilai yang Sudah Diinput</h3>
            <?php
            $my_nilai = $pdo->prepare("
                SELECT u.nama_lengkap, n.mapel, n.nilai, n.semester 
                FROM nilai n
                JOIN siswa s ON n.siswa_id = s.id
                JOIN users u ON s.user_id = u.id
                ORDER BY u.nama_lengkap, n.semester
            ");
            $my_nilai->execute();
            $data = $my_nilai->fetchAll();
            ?>
            <?php if (count($data) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Nilai</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['mapel']) ?></td>
                                <td><?= number_format($row['nilai'], 1) ?></td>
                                <td><?= htmlspecialchars($row['semester']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Tombol Export CSV -->
                <div style="text-align: right; margin-top: 1rem;">
                    <a href="export_nilai_csv.php" 
                       style="background: #4caf50; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        📥 Export ke CSV (Buka di Excel)
                    </a>
                </div>

            <?php else: ?>
                <p style="color: #888;">Belum ada nilai yang diinput.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>