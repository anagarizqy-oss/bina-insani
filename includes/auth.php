<?php
// includes/auth.php
session_start();
function must_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../login.php");
        exit;
    }
}

function must_be($allowed_roles) {
    must_login();
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: ../../login.php");
        exit;
    }
}
?>