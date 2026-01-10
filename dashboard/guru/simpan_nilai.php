<?php
// dashboard/guru/simpan_nilai.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['guru']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kelas_id = (int)$_POST['kelas_id'];
    $mapel = $_POST['mapel'];
    $siswa_ids = $_POST['siswa_id'];

    if (empty($siswa_ids)) {
        header("Location: input_nilai.php?kelas_id=$kelas_id");
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt_check = $pdo->prepare("SELECT id FROM nilai WHERE siswa_id = ? AND mapel = ?");
        $stmt_insert = $pdo->prepare("
            INSERT INTO nilai (siswa_id, mapel, uh1, uh2, uh3, uh4, tugas1, tugas2, tugas3, tugas4, uts, uas)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_update = $pdo->prepare("
            UPDATE nilai SET 
                uh1 = ?, uh2 = ?, uh3 = ?, uh4 = ?,
                tugas1 = ?, tugas2 = ?, tugas3 = ?, tugas4 = ?,
                uts = ?, uas = ?
            WHERE id = ?
        ");

        foreach ($siswa_ids as $sid) {
            // Collect Input
            $uh1 = $_POST['uh1'][$sid] !== '' ? $_POST['uh1'][$sid] : null;
            $uh2 = $_POST['uh2'][$sid] !== '' ? $_POST['uh2'][$sid] : null;
            $uh3 = $_POST['uh3'][$sid] !== '' ? $_POST['uh3'][$sid] : null;
            $uh4 = $_POST['uh4'][$sid] !== '' ? $_POST['uh4'][$sid] : null;

            $tugas1 = $_POST['tugas1'][$sid] !== '' ? $_POST['tugas1'][$sid] : null;
            $tugas2 = $_POST['tugas2'][$sid] !== '' ? $_POST['tugas2'][$sid] : null;
            $tugas3 = $_POST['tugas3'][$sid] !== '' ? $_POST['tugas3'][$sid] : null;
            $tugas4 = $_POST['tugas4'][$sid] !== '' ? $_POST['tugas4'][$sid] : null;

            $uts = $_POST['uts'][$sid] !== '' ? $_POST['uts'][$sid] : null;
            $uas = $_POST['uas'][$sid] !== '' ? $_POST['uas'][$sid] : null;

            // Check if exists
            $stmt_check->execute([$sid, $mapel]);
            $existing = $stmt_check->fetch();

            if ($existing) {
                // Update
                $stmt_update->execute([
                    $uh1,
                    $uh2,
                    $uh3,
                    $uh4,
                    $tugas1,
                    $tugas2,
                    $tugas3,
                    $tugas4,
                    $uts,
                    $uas,
                    $existing['id']
                ]);
            } else {
                // Insert
                // Only insert if at least one value is not empty to avoid spamming empty rows? 
                // Actually, teacher might want to initialize with empty, but usually we insert.
                $stmt_insert->execute([
                    $sid,
                    $mapel,
                    $uh1,
                    $uh2,
                    $uh3,
                    $uh4,
                    $tugas1,
                    $tugas2,
                    $tugas3,
                    $tugas4,
                    $uts,
                    $uas
                ]);
            }
        }

        $pdo->commit();
        $_SESSION['success_message'] = "Nilai berhasil disimpan.";
        header("Location: input_nilai.php?kelas_id=$kelas_id");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Gagal menyimpan nilai: " . $e->getMessage());
    }
} else {
    header("Location: input_nilai.php");
    exit;
}
