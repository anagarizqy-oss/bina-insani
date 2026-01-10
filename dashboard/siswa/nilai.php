<?php
// dashboard/siswa/nilai.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];

// Get Student ID
$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();
$siswa_id = $siswa['id'];

// Fetch Grades
$stmt_nilai = $pdo->prepare("SELECT * FROM nilai WHERE siswa_id = ? ORDER BY mapel ASC");
$stmt_nilai->execute([$siswa_id]);
$nilai_list = $stmt_nilai->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            <span>Nilai Akademik</span>
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Daftar Nilai Akademik</h2>

        <div class="card">
            <?php if (count($nilai_list) > 0): ?>
                <div class="table-container" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: left;">Mata Pelajaran</th>
                                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: center;">Rerata UH</th>
                                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: center;">Rerata Tugas</th>
                                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: center;">UTS</th>
                                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: center;">UAS</th>
                                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: center;">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nilai_list as $n):
                                // Calculate Averages
                                $uh_total = ($n['uh1'] + $n['uh2'] + $n['uh3'] + $n['uh4']);
                                $uh_count = 0;
                                if ($n['uh1'] !== null) $uh_count++;
                                if ($n['uh2'] !== null) $uh_count++;
                                if ($n['uh3'] !== null) $uh_count++;
                                if ($n['uh4'] !== null) $uh_count++;
                                $uh_avg = $uh_count > 0 ? $uh_total / $uh_count : 0;

                                $tugas_total = ($n['tugas1'] + $n['tugas2'] + $n['tugas3'] + $n['tugas4']);
                                $tugas_count = 0;
                                if ($n['tugas1'] !== null) $tugas_count++;
                                if ($n['tugas2'] !== null) $tugas_count++;
                                if ($n['tugas3'] !== null) $tugas_count++;
                                if ($n['tugas4'] !== null) $tugas_count++;
                                $tugas_avg = $tugas_count > 0 ? $tugas_total / $tugas_count : 0;

                                // Simple Final Score Formula: (UH + Tugas + UTS + UAS) / 4 weighted?
                                // Let's simplify: 30% UH, 20% Tugas, 20% UTS, 30% UAS
                                $uts = $n['uts'] ?? 0;
                                $uas = $n['uas'] ?? 0;

                                $na = ($uh_avg * 0.3) + ($tugas_avg * 0.2) + ($uts * 0.2) + ($uas * 0.3);
                            ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($n['mapel']) ?></td>
                                    <td style="padding: 12px; text-align: center;"><?= number_format($uh_avg, 1) ?></td>
                                    <td style="padding: 12px; text-align: center;"><?= number_format($tugas_avg, 1) ?></td>
                                    <td style="padding: 12px; text-align: center;"><?= number_format($uts, 1) ?></td>
                                    <td style="padding: 12px; text-align: center;"><?= number_format($uas, 1) ?></td>
                                    <td style="padding: 12px; text-align: center; font-weight: bold; color: #2575fc;">
                                        <?= number_format($na, 1) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 30px; text-align: center; color: #888;">
                    <i class="fas fa-clipboard-list" style="font-size: 3rem; margin-bottom: 15px; display: block; color: #eee;"></i>
                    Belum ada nilai yang diinputkan oleh guru.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>

</html>