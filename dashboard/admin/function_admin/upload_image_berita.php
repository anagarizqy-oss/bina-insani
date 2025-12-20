<?php
// dashboard/admin/function_admin/upload_image_berita.php

include '../../../includes/auth.php';
must_be(['admin']);

header('Content-Type: application/json');

if (empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'File tidak diterima']);
    exit;
}

$allowed = ['jpg', 'jpeg', 'png', 'webp'];
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Format gambar tidak diizinkan']);
    exit;
}

if ($_FILES['file']['size'] > 2 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'Ukuran maksimal 2MB']);
    exit;
}

$folder = '../../../assets/img/berita/';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$filename = uniqid('berita_') . '.' . $ext;
$path = $folder . $filename;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menyimpan file']);
    exit;
}

/**
 * WAJIB URL PUBLIK
 */
$baseUrl = '/sma-bina-insani';
echo json_encode([
    'location' => $baseUrl . '/assets/img/berita/' . $filename
]);
exit;
