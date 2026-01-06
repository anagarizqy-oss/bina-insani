<?php
// dashboard/siswa/nilai.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();
$siswa_id = $siswa['id'];

// Get Grades
$stmt_nilai = $pdo->prepare("SELECT * FROM nilai WHERE siswa_id = ? ORDER BY semester, mapel");
$stmt_nilai->execute([$siswa_id]);
$nilai_data = $stmt_nilai->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            Transkrip Nilai
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Daftar Nilai Akademik</h2>

        <div class="table-container">
            <?php if (count($nilai_data) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Semester</th>
                            <th>Nilai</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nilai_data as $row): ?>
                            <?php
                            $n = $row['nilai'];
                            $predikat = 'E';
                            if ($n >= 90) $predikat = 'A';
                            elseif ($n >= 80) $predikat = 'B';
                            elseif ($n >= 70) $predikat = 'C';
                            elseif ($n >= 60) $predikat = 'D';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['mapel']) ?></td>
                                <td><?= htmlspecialchars($row['semester']) ?></td>
                                <td><?= number_format($row['nilai'], 1) ?></td>
                                <td><span style="font-weight: bold;"><?= $predikat ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert warning">Belum ada data nilai.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>

</html>