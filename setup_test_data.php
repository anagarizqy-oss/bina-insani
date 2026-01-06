<?php
// setup_test_data.php
include 'config/db.php';

// 1. Create User
$username = 'siswa_test';
$password = password_hash('password123', PASSWORD_BCRYPT);
$nama = 'Siswa Percobaan';
$role = 'siswa';

// Check if exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
    $user_id = $user['id'];
    echo "User $username already exists (ID: $user_id).<br>";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $nama, $role]);
    $user_id = $pdo->lastInsertId();
    echo "User $username created (ID: $user_id).<br>";
}

// 2. Create Siswa Profile
$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();

if ($siswa) {
    $siswa_id = $siswa['id'];
    echo "Siswa profile already exists (ID: $siswa_id).<br>";
} else {
    $stmt = $pdo->prepare("INSERT INTO siswa (user_id, nis, kelas, jurusan) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, '123456', 'XII IPA 1', 'IPA']);
    $siswa_id = $pdo->lastInsertId();
    echo "Siswa profile created (ID: $siswa_id).<br>";
}

// 3. Insert Presensi Data
$stmt = $pdo->prepare("INSERT INTO presensi (siswa_id, tanggal, status) VALUES (?, CURDATE(), 'Hadir')");
try {
    $stmt->execute([$siswa_id]);
    echo "Presensi inserted.<br>";
} catch (Exception $e) {
    echo "Presensi duplicate/error.<br>";
}

// 4. Insert Nilai Data
$stmt = $pdo->prepare("INSERT INTO nilai (siswa_id, mapel, nilai, semester) VALUES (?, 'Matematika', 85, 'Ganjil')");
try {
    $stmt->execute([$siswa_id]);
    echo "Nilai inserted.<br>";
} catch (Exception $e) {
    echo "Nilai duplicate/error.<br>";
}

// 5. Insert SPP Data
$stmt = $pdo->prepare("INSERT INTO spp (siswa_id, bulan, status) VALUES (?, 'Januari', 'Lunas')");
try {
    $stmt->execute([$siswa_id]);
    echo "SPP inserted.<br>";
} catch (Exception $e) {
    echo "SPP duplicate/error.<br>";
}

echo "<br>Setup Complete. Login with user: <b>$username</b> pass: <b>password123</b>";
