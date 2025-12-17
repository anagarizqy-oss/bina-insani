<?php
session_start();
include 'config/db.php';
include 'includes/csrf.php';
include 'includes/navbar.php';
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
            header("Location: dashboard/" . $user['role'] . "/index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}

// Proses Registrasi
if ($_POST && isset($_POST['register'])) {
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $error = "Permintaan tidak valid.";
    } else {
        $nama = clean($_POST['nama']);
        $username = clean($_POST['username']);
        $password = $_POST['password'];
        $konfirmasi = $_POST['konfirmasi'];
        $nip_nis = clean($_POST['nip_nis']);
        $role = $_POST['role'];

        if (strlen($password) < 6) {
            $error = "Password minimal 6 karakter.";
        } elseif ($password !== $konfirmasi) {
            $error = "Konfirmasi password tidak cocok.";
        } else {
            $cek = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $cek->execute([$username]);
            if ($cek->rowCount() > 0) {
                $error = "Username sudah digunakan.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $pdo->beginTransaction();
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$username, $hashed_password, $role, $nama]);
                    $user_id = $pdo->lastInsertId();

                    if ($role === 'siswa') {
                        $stmt = $pdo->prepare("INSERT INTO siswa (user_id, nis, kelas) VALUES (?, ?, ?)");
                        $stmt->execute([$user_id, $nip_nis, 'XII IPA 1']);
                    } elseif ($role === 'guru') {
                        $stmt = $pdo->prepare("INSERT INTO guru (user_id, nip, is_wali_kelas, kelas_wali) VALUES (?, ?, 0, ?)");
                        $stmt->execute([$user_id, $nip_nis, 'XII IPA 1']);
                    }

                    $pdo->commit();
                    $success = "Akun berhasil dibuat! Silakan login.";
                } catch (Exception $e) {
                    $pdo->rollback();
                    $error = "Gagal membuat akun. Coba lagi.";
                }
            }
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
    <title>Login / Daftar - SMA BINA INSANI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* === BODY & GRADIENT === */
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }


        /* === LOGIN CONTAINER === */
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            width: 90%;
            max-width: 500px;
            text-align: center;
            margin-top: 2rem;
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #2575fc;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .subtitle {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        /* === TABS === */
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1.5rem;
            justify-content: center;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            border: none;
            background: transparent;
            position: relative;
        }

        .tab.active {
            background: #2575fc;
            color: white;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .tab::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 2px;
            background: #2575fc;
            transition: width 0.3s;
        }

        .tab.active::after {
            width: 100%;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* === FORM INPUT === */
        input,
        select,
        button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            background: #2575fc;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        button:hover {
            background: #1a68e8;
        }

        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .switch-form {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .switch-form a {
            color: #2575fc;
            text-decoration: none;
        }

        .switch-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- LOGIN CONTAINER -->
    <div class="login-container">
        <h2>SMA BINA INSANI WONOGIRI</h2>
        <p class="subtitle">Silakan login atau daftar akun baru</p>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <div class="tabs">
            <div class="tab active" data-tab="login">Login Akun</div>
            <div class="tab" data-tab="register">Daftar Akun Baru</div>
        </div>

        <!-- Form Login -->
        <div class="tab-content active" id="login">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Masuk</button>
            </form>
            <div class="switch-form">
                Belum punya akun? <a href="#" onclick="switchTab('register')">Daftar di sini</a>
            </div>
        </div>

        <!-- Form Registrasi -->
        <div class="tab-content" id="register">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password (min 6 karakter)" required>
                <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
                <select name="role" required>
                    <option value="">-- Pilih Peran --</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                </select>
                <input type="text" name="nip_nis" placeholder="NIS (Siswa) atau NIP (Guru)" required>
                <button type="submit" name="register">Daftar Sekarang</button>
            </form>
            <div class="switch-form">
                Sudah punya akun? <a href="#" onclick="switchTab('login')">Login di sini</a>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));

            document.querySelector(`.tab[data-tab="${tabName}"]`).classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        // Aktifkan tab pertama saat load
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.querySelector('.tab.active');
            if (activeTab) {
                const tabName = activeTab.getAttribute('data-tab');
                document.getElementById(tabName).classList.add('active');
            }
        });
    </script>
</body>

</html>