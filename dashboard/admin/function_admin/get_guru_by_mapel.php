<?php
// dashboard/admin/function_admin/get_guru_by_mapel.php
include '../../../config/db.php';

// Simple API, minimal security for internal use via AJAX
// In production, check session/auth here too if strictness required

if (isset($_GET['mapel'])) {
    $mapel = $_GET['mapel'];
    try {
        $stmt = $pdo->prepare("SELECT id, nama_lengkap FROM guru WHERE mata_pelajaran = ? ORDER BY nama_lengkap ASC");
        $stmt->execute([$mapel]);
        $guru = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($guru);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
