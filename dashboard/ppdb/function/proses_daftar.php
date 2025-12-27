<?php
// proses_daftar.php
session_start();

// === 1. KONFIGURASI DATABASE ===
$host = 'localhost';
$dbname = 'sma_bina_insani';  // sesuaikan jika berbeda
$username = 'root';           // ganti sesuai server Anda
$password = '';               // ganti jika ada password

// === 2. KONEKSI DATABASE ===
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// === 3. AMBIL DATA DARI FORM ===
// Ambil semua data dari $_POST dan $_FILES
$jenis_pendaftaran = $conn->real_escape_string($_POST['jenis_pendaftaran']);
$jalur_pendaftaran = $conn->real_escape_string($_POST['jalur_pendaftaran']);
$nik = $conn->real_escape_string($_POST['nik']);
$sekolah_asal = $conn->real_escape_string($_POST['sekolah_asal']);
$nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
$nisn = $conn->real_escape_string($_POST['nisn']);
$jenis_kelamin = $conn->real_escape_string($_POST['jenis_kelamin']);
$tempat_lahir = $conn->real_escape_string($_POST['tempat_lahir']);
$tanggal_lahir = $conn->real_escape_string($_POST['tanggal_lahir']);
$agama = $conn->real_escape_string($_POST['agama']);
$kewarganegaraan = $conn->real_escape_string($_POST['kewarganegaraan']);
$alamat_jalan = $conn->real_escape_string($_POST['alamat_jalan']);
$desa_kelurahan = $conn->real_escape_string($_POST['desa_kelurahan']);
$kecamatan = $conn->real_escape_string($_POST['kecamatan']);
$kabupaten_kota = $conn->real_escape_string($_POST['kabupaten_kota']);
$kode_pos = $conn->real_escape_string($_POST['kode_pos']);
$tempat_tinggal = $conn->real_escape_string($_POST['tempat_tinggal']);
$anak_ke = (int)$_POST['anak_ke'];
$jumlah_saudara_kandung = (int)$_POST['jumlah_saudara_kandung'];
$memiliki_kip = ($_POST['memiliki_kip'] === 'ya') ? 1 : 0;
$akan_menerima_kip = ($_POST['akan_menerima_kip'] === 'ya') ? 1 : 0;

$nama_ayah = $conn->real_escape_string($_POST['nama_ayah']);
$nik_ayah = $conn->real_escape_string($_POST['nik_ayah']);
$tempat_lahir_ayah = $conn->real_escape_string($_POST['tempat_lahir_ayah']);
$tanggal_lahir_ayah = $conn->real_escape_string($_POST['tanggal_lahir_ayah']);
$pendidikan_ayah = $conn->real_escape_string($_POST['pendidikan_ayah']);
$pekerjaan_ayah = $conn->real_escape_string($_POST['pekerjaan_ayah']);
$penghasilan_ayah = $conn->real_escape_string($_POST['penghasilan_ayah']);

$nama_ibu = $conn->real_escape_string($_POST['nama_ibu']);
$nik_ibu = $conn->real_escape_string($_POST['nik_ibu']);
$tempat_lahir_ibu = $conn->real_escape_string($_POST['tempat_lahir_ibu']);
$tanggal_lahir_ibu = $conn->real_escape_string($_POST['tanggal_lahir_ibu']);
$pendidikan_ibu = $conn->real_escape_string($_POST['pendidikan_ibu']);
$pekerjaan_ibu = $conn->real_escape_string($_POST['pekerjaan_ibu']);
$penghasilan_ibu = $conn->real_escape_string($_POST['penghasilan_ibu']);

$no_hp = $conn->real_escape_string($_POST['no_hp']);
$email = $conn->real_escape_string($_POST['email']);

// === 4. VALIDASI CAPTCHA (opsional tapi disarankan) ===
// if ($_POST['captcha'] !== $_SESSION['captcha']) {
//     die("CAPTCHA salah! Silakan kembali dan coba lagi.");
// }

// === 5. HANDLE UPLOAD FILE ===
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function uploadFile($file, $prefix, $upload_dir) {
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    if ($file['size'] > 500000) return null; // max 500KB
    $new_name = $prefix . uniqid() . "." . $ext;
    $path = $upload_dir . $new_name;
    if (move_uploaded_file($file['tmp_name'], $path)) {
        return $path;
    }
    return null;
}

$pas_foto = uploadFile($_FILES['pasfoto'], 'pasfoto_', $upload_dir);
$kartu_keluarga = uploadFile($_FILES['kk'], 'kk_', $upload_dir);
$akta_lahir = uploadFile($_FILES['akta'], 'akta_', $upload_dir);
$kip = uploadFile($_FILES['kip'], 'kip_', $upload_dir);
$bukti_pembayaran = uploadFile($_FILES['pembayaran'], 'pembayaran_', $upload_dir);

// Pastikan file wajib diupload
if (!$pas_foto || !$kartu_keluarga || !$akta_lahir) {
    die("Gagal mengunggah dokumen wajib. Pastikan file sesuai format dan ukuran.");
}

// === 6. TRANSAKSI DATABASE ===
$conn->autocommit(false);

try {
    // Insert calon_siswa
    $sql1 = "INSERT INTO calon_siswa (
        jenis_pendaftaran, jalur_pendaftaran, nik, sekolah_asal,
        nama_lengkap, nisn, jenis_kelamin, tempat_lahir, tanggal_lahir,
        agama, kewarganegaraan, alamat_jalan, desa_kelurahan,
        kecamatan, kabupaten_kota, kode_pos, tempat_tinggal,
        anak_ke, jumlah_saudara_kandung, memiliki_kip, akan_menerima_kip
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param(
        "ssssssssssssssssiiii",
        $jenis_pendaftaran, $jalur_pendaftaran, $nik, $sekolah_asal,
        $nama_lengkap, $nisn, $jenis_kelamin, $tempat_lahir, $tanggal_lahir,
        $agama, $kewarganegaraan, $alamat_jalan, $desa_kelurahan,
        $kecamatan, $kabupaten_kota, $kode_pos, $tempat_tinggal,
        $anak_ke, $jumlah_saudara_kandung, $memiliki_kip, $akan_menerima_kip
    );
    $stmt1->execute();
    $calon_id = $conn->insert_id;

    // Insert orang_tua
    $sql2 = "INSERT INTO orang_tua (
        calon_siswa_id, nama_ayah, nik_ayah, tempat_lahir_ayah, tanggal_lahir_ayah,
        pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah,
        nama_ibu, nik_ibu, tempat_lahir_ibu, tanggal_lahir_ibu,
        pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param(
        "issssssssssssss",
        $calon_id, $nama_ayah, $nik_ayah, $tempat_lahir_ayah, $tanggal_lahir_ayah,
        $pendidikan_ayah, $pekerjaan_ayah, $penghasilan_ayah,
        $nama_ibu, $nik_ibu, $tempat_lahir_ibu, $tanggal_lahir_ibu,
        $pendidikan_ibu, $pekerjaan_ibu, $penghasilan_ibu
    );
    $stmt2->execute();

    // Insert dokumen
    $sql3 = "INSERT INTO dokumen (
        calon_siswa_id, pas_foto, kartu_keluarga, akta_lahir, kip, bukti_pembayaran
    ) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param(
        "isssss",
        $calon_id, $pas_foto, $kartu_keluarga, $akta_lahir, $kip, $bukti_pembayaran
    );
    $stmt3->execute();

    // Insert kontak
    $sql4 = "INSERT INTO kontak (calon_siswa_id, no_hp, email) VALUES (?, ?, ?)";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("iss", $calon_id, $no_hp, $email);
    $stmt4->execute();

    $conn->commit();
    $conn->close();

    // Redirect ke halaman sukses
    header("Location: sukses.php?nisn=" . urlencode($nisn));
    exit();

} catch (Exception $e) {
    $conn->rollback();
    $conn->close();
    die("Terjadi kesalahan saat mendaftar: " . htmlspecialchars($e->getMessage()));
}
?>