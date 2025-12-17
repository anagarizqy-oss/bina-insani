<?php
// includes/csrf.php
if (session_status() == PHP_SESSION_NONE) session_start();

function generate_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function clean($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}
?>