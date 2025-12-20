<?php
// dashboard/admin/function_admin/add_ekskul.php
include '../../../includes/auth.php';
include '../../../config/db.php';
include '../../../includes/csrf.php';

must_be(['admin']);

$error = '';
$success = '';
$data = [];
$is_edit = false;

// Fetch Options for Dropdowns
$guru_list = $pdo->query("SELECT nama_lengkap FROM guru ORDER BY nama_lengkap ASC")->fetchAll(PDO::FETCH_COLUMN);
$siswa_list = $pdo->query("SELECT nama_lengkap FROM siswa ORDER BY nama_lengkap ASC")->fetchAll(PDO::FETCH_COLUMN);

// CHECK IF EDIT MODE
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM ekstrakurikuler WHERE id_ekskul = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if ($data) {
        $is_edit = true;
    } else {
        $error = "Data tidak ditemukan.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_ekskul = clean($_POST['nama_ekskul']);
    $guru = clean($_POST['guru_pembimbing']);
    $ketua = clean($_POST['ketua']);
    $wakil = clean($_POST['wakil_ketua']);
    $anggota = (int)$_POST['jumlah_anggota'];

    // Validation
    if (empty($nama_ekskul) || empty($guru) || empty($ketua) || empty($wakil)) {
        $error = "Nama Ekskul, Guru Pembimbing, Ketua, dan Wakil Ketua wajib diisi.";
    } elseif ($ketua === $wakil) {
        $error = "Ketua dan Wakil Ketua tidak boleh orang yang sama.";
    } elseif (($anggota + 2) <= 8) {
        // Total = Anggota Biasa + Ketua (1) + Wakil (1)
        $error = "Minimal total anggota adalah lebih dari 8 orang (termasuk Ketua dan Wakil Ketua).";
    } else {
        try {
            // HANDLE FILE UPLOAD
            $upload_dir = '../../../assets/img/ekskul/';

            // Function to handle single file upload
            function uploadFile($fileInputName, $targetDir, $prefix)
            {
                if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
                    $fileName = $_FILES[$fileInputName]['name'];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    if (in_array($fileExt, $allowed)) {
                        $newFileName = $prefix . '_' . uniqid() . '.' . $fileExt;
                        $dest_path = $targetDir . $newFileName;

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            return 'assets/img/ekskul/' . $newFileName; // Return relative path for DB
                        }
                    }
                }
                return null;
            }

            $cover_path = uploadFile('cover_image', $upload_dir, 'cover');
            $bg_path = uploadFile('background_image', $upload_dir, 'bg');

            if ($is_edit) {
                // Prepare dynamic Update query
                $sql = "UPDATE ekstrakurikuler SET nama_ekskul=?, guru_pembimbing=?, ketua=?, wakil_ketua=?, jumlah_anggota=?";
                $params = [$nama_ekskul, $guru, $ketua, $wakil, $anggota];

                if ($cover_path) {
                    $sql .= ", cover_image=?";
                    $params[] = $cover_path;
                }
                if ($bg_path) {
                    $sql .= ", background_image=?";
                    $params[] = $bg_path;
                }

                $sql .= " WHERE id_ekskul=?";
                $params[] = $id;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
            } else {
                $stmt = $pdo->prepare("INSERT INTO ekstrakurikuler (nama_ekskul, guru_pembimbing, ketua, wakil_ketua, jumlah_anggota, cover_image, background_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nama_ekskul, $guru, $ketua, $wakil, $anggota, $cover_path, $bg_path]);
            }
            header("Location: ../kelola_ekstrakurikuler.php");
            exit;
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Ekstrakurikuler - Admin</title>
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
            padding: 5px;
            border-color: #ddd;
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .file-input-group {
            margin-top: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="main-content" style="margin-left: 0; width: 100%; max-width: 600px; margin: 2rem auto;">
        <div class="card">
            <h2><?= $is_edit ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler' ?></h2>

            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <label>Nama Ekstrakurikuler *</label>
                <input type="text" name="nama_ekskul" required value="<?= $is_edit ? htmlspecialchars($data['nama_ekskul']) : '' ?>">

                <label>Guru Pembimbing *</label>
                <select name="guru_pembimbing" class="select2" required style="width: 100%;">
                    <option value="">-- Cari Guru --</option>
                    <?php foreach ($guru_list as $nama): ?>
                        <option value="<?= $nama ?>" <?= ($is_edit && $data['guru_pembimbing'] == $nama) ? 'selected' : '' ?>><?= $nama ?></option>
                    <?php endforeach; ?>
                </select>

                <div style="margin-top: 15px;">
                    <label>Ketua Ekskul *</label>
                    <select name="ketua" id="ketua" class="select2" required style="width: 100%;">
                        <option value="">-- Cari Siswa --</option>
                        <?php foreach ($siswa_list as $nama): ?>
                            <option value="<?= $nama ?>" <?= ($is_edit && $data['ketua'] == $nama) ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-top: 15px;">
                    <label>Wakil Ketua *</label>
                    <select name="wakil_ketua" id="wakil" class="select2" required style="width: 100%;">
                        <option value="">-- Cari Siswa --</option>
                        <?php foreach ($siswa_list as $nama): ?>
                            <option value="<?= $nama ?>" <?= ($is_edit && $data['wakil_ketua'] == $nama) ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-top: 15px;">
                    <label>Jumlah Anggota Biasa (Selain Ketua & Wakil) *</label>
                    <input type="number" name="jumlah_anggota" min="0" required value="<?= $is_edit ? htmlspecialchars($data['jumlah_anggota']) : '0' ?>">
                    <small style="color: #666;">Total anggota (termasuk Ketua & Wakil) harus lebih dari 8 orang.</small>
                </div>

                <div class="file-input-group">
                    <label>Cover Image (Opsional)</label>
                    <?php if ($is_edit && !empty($data['cover_image'])): ?>
                        <br><img src="../../../<?= htmlspecialchars($data['cover_image']) ?>" height="80" style="margin-bottom:5px; border-radius:4px;"><br>
                    <?php endif; ?>
                    <input type="file" name="cover_image" accept="image/*">
                    <small>Format: JPG, PNG, WEBP. Ditampilkan di daftar ekskul.</small>
                </div>

                <div class="file-input-group">
                    <label>Background Image (Opsional)</label>
                    <?php if ($is_edit && !empty($data['background_image'])): ?>
                        <br><img src="../../../<?= htmlspecialchars($data['background_image']) ?>" height="80" style="margin-bottom:5px; border-radius:4px;"><br>
                    <?php endif; ?>
                    <input type="file" name="background_image" accept="image/*">
                    <small>Format: JPG, PNG, WEBP. Ditampilkan di halaman detail.</small>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="../kelola_ekstrakurikuler.php" style="flex: 1; padding: 12px; text-align: center; border: 1px solid #ddd; border-radius: 8px; color: #666;">Batal</a>
                    <button type="submit" style="flex: 2;"><?= $is_edit ? 'Simpan Perubahan' : 'Simpan Data' ?></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih / Cari...",
                allowClear: true
            });

            // Prevent selecting same person
            $('#ketua, #wakil').on('change', function() {
                var ketua = $('#ketua').val();
                var wakil = $('#wakil').val();

                if (ketua && wakil && ketua === wakil) {
                    alert('Ketua dan Wakil Ketua tidak boleh orang yang sama!');
                    $(this).val(null).trigger('change');
                }
            });
        });
    </script>
</body>

</html>