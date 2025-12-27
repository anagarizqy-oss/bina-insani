<?php
// dashboard/admin/function_admin/simpan_galeri.php

include '../../../includes/auth.php';
include '../../../includes/csrf.php';
include '../../../config/db.php';

// Pastikan hanya admin yang bisa akses
must_be(['admin']);

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_foto'])) {

    // Validasi Token CSRF
    if (!verify_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error_message'] = "Permintaan tidak valid (CSRF Token mismatch).";
        header("Location: ../kelola_galeri.php");
        exit;
    }

    $deskripsi = clean($_POST['deskripsi']);
    $tanggal_foto = $_POST['tanggal_foto'];

    // Validasi input
    if (empty($deskripsi) || empty($tanggal_foto)) {
        $_SESSION['error_message'] = "Deskripsi dan Tanggal Foto wajib diisi.";
        header("Location: ../kelola_galeri.php");
        exit;
    }

    // Upload Foto
    if (empty($_FILES['foto']['name'])) {
        $_SESSION['error_message'] = "File foto wajib diupload.";
        header("Location: ../kelola_galeri.php");
        exit;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $_SESSION['error_message'] = "Format foto tidak valid. Gunakan JPG, JPEG, PNG, atau WEBP.";
        header("Location: ../kelola_galeri.php");
        exit;
    }

    if ($_FILES['foto']['size'] > 5 * 1024 * 1024) { // 5MB limit
        $_SESSION['error_message'] = "Ukuran foto maksimal 5MB.";
        header("Location: ../kelola_galeri.php");
        exit;
    }

    // Path penyimpanan
    // Karena file ini ada di function_admin, kita perlu naik 3 level untuk ke root, lalu ke assets
    // Tapiii... path yang kita simpan di DB adalah relative dari root web.
    // Dan saat move_uploaded_file, kita butuh path fisik relatif dari file php ini.

    $target_dir_relative_to_script = '../../../assets/img/galeri/';
    if (!is_dir($target_dir_relative_to_script)) mkdir($target_dir_relative_to_script, 0777, true);

    $filename = uniqid('galeri_') . '.' . $ext;
    $target_file = $target_dir_relative_to_script . $filename;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        // Simpan path untuk diakses dari browser (relative terhadap root project)
        $url_foto = 'assets/img/galeri/' . $filename;

        // INSERT ke database
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO galeri (url_foto, deskripsi, tanggal_foto) VALUES (?, ?, ?)"
            );
            $stmt->execute([$url_foto, $deskripsi, $tanggal_foto]);

            $_SESSION['success_message'] = "Foto berhasil ditambahkan ke galeri.";
            header("Location: ../kelola_galeri.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Gagal menyimpan ke database: " . $e->getMessage();
            // Hapus file jika gagal insert db
            if (file_exists($target_file)) unlink($target_file);

            header("Location: ../kelola_galeri.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Gagal mengupload file ke folder tujuan.";
        header("Location: ../kelola_galeri.php");
        exit;
    }
} else {
    // Jika akses langsung tanpa post
    header("Location: ../kelola_galeri.php");
    exit;
}
