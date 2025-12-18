<?php
session_start();
include 'config/db.php';

if (isset($_SESSION['login_log_id'])) {
    $log_id = $_SESSION['login_log_id'];
    try {
        $stmt = $pdo->prepare("UPDATE login_logs SET logout_time = NOW(), is_active = 0 WHERE id = ?");
        $stmt->execute([$log_id]);
    } catch (PDOException $e) {
        // Ignore error
    }
}

session_destroy();
header("Location: login.php");
exit;
