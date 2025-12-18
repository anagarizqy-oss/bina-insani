<?php
// dashboard/admin/upload_image.php

header('Content-Type: application/json');

// Konfigurasi folder upload
$upload_dir = 'uploads/';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$max_size = 2 * 1024 * 1024; // 2MB

// Pastikan user login sebagai admin (opsional, tapi disarankan)
// include '../../includes/auth.php';
// if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     echo json_encode(['error' => ['message' => 'Unauthorized']]);
//     exit;
// }

if ($_FILES['file']['name']) {
    $filename = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $file_size = $_FILES['file']['size'];

    // Validasi ekstensi
    if (!in_array($file_ext, $allowed_extensions)) {
        http_response_code(400);
        echo json_encode(['error' => ['message' => 'Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan.']]);
        exit;
    }

    // Validasi ukuran
    if ($file_size > $max_size) {
        http_response_code(400);
        echo json_encode(['error' => ['message' => 'Ukuran file terlalu besar. Maksimal 2MB.']]);
        exit;
    }

    // Generate nama file unik agar tidak bentrok
    $new_filename = uniqid() . '.' . $file_ext;
    $target_file = $upload_dir . $new_filename;

    // Cek apakah folder uploads ada, jika tidak buat
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($tmp_name, $target_file)) {
        // Berhasil upload
        // Kembalikan path absolut web agar bisa diakses dari mana saja (admin & publik)
        // Dapatkan path folder saat ini (dashboard/admin)
        $current_script_path = dirname($_SERVER['SCRIPT_NAME']);

        // Gabungkan untuk mendapatkan URL lengkap gambar
        // Contoh: /sma-bina-insani/dashboard/admin/uploads/gambar.jpg
        $location = $current_script_path . '/' . $target_file;

        echo json_encode(['location' => $location]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => ['message' => 'Gagal mengupload gambar.']]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => ['message' => 'Tidak ada file yang diunggah.']]);
}
