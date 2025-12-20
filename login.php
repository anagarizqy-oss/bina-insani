<?php
session_start();
include 'config/db.php';
include 'includes/csrf.php';
// include 'includes/navbar.php';
$error = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/" . $_SESSION['role'] . "/index.php");
    exit;
}

// Proses Login
if ($_POST && isset($_POST['login'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $error = "Permintaan tidak valid.";
    } else {
        $username = clean($_POST['username']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            unset($_SESSION['csrf_token']);

            // Login Logging
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            try {
                $stmt_log = $pdo->prepare("INSERT INTO login_logs (user_id, username, role, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
                $stmt_log->execute([$user['id'], $user['username'], $user['role'], $ip_address, $user_agent]);
                $_SESSION['login_log_id'] = $pdo->lastInsertId();
            } catch (PDOException $e) {
                // Silent failure for logging? Or log to error file. 
                // Proceed with login even if sensitive log fails.
            }

            header("Location: dashboard/" . $user['role'] . "/index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}

// Registration logic removed

$csrf_token = generate_token();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Daftar - SMA BINA INSANI</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
    <!-- LOGIN CONTAINER -->
    <div class="login-container">
        <h2>SMA BINA INSANI WONOGIRI</h2>
        <p class="subtitle">Silakan login untuk melanjutkan</p>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <!-- Form Login -->
        <div id="login">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Masuk</button>
            </form>
        </div>
    </div>

    <!-- Script removed as tabs are no longer needed -->
</body>


</html>

