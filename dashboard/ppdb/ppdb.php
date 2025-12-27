<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SMA Bina Insani Wonogiri</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        .back-button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: background 0.3s;
            text-decoration: none;
            font-weight: 500;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 80vh;
        }

        .form-title {
            text-align: center;
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .section-title {
            background: #6b8e23;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 20px 0 15px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .form-label {
            width: 250px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .form-label span.required {
            color: red;
            margin-left: 3px;
        }

        .form-input,
        .form-select,
        .form-textarea,
        .form-date,
        .captcha-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus,
        .form-date:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-date {
            background: white;
        }

        .file-input-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .file-label {
            background: #f3f4f6;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .file-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        .captcha-section {
            margin: 20px 0;
        }

        .captcha-image {
            width: 120px;
            height: 40px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 3px;
            user-select: none;
            cursor: pointer;
            margin-top: 5px;
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 15px 0;
        }

        .checkbox-container input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-top: 4px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #2563eb;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }

            .form-group {
                flex-direction: column;
                gap: 5px;
            }

            .form-label {
                width: 100%;
                text-align: left;
            }

            .file-input-wrapper {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="../../index.php" class="back-button">← Kembali ke Beranda</a>

        <div class="form-card">
            <h1 class="form-title">PPDB SMA BINA INSANI WONOGIRI</h1>
            <p class="form-subtitle">Silakan isi formulir pendaftaran di bawah ini</p>

            <!-- Form Utama -->
            <form action="function/proses_daftar.php" method="POST" enctype="multipart/form-data">

                <!-- Bagian 1: Informasi Pendaftaran Dasar -->
                <div class="section-title">INFORMASI PENDAFTARAN DASAR</div>
                <div class="form-group">
                    <label class="form-label">Jenis Pendaftaran <span class="required">*</span></label>
                    <select name="jenis_pendaftaran" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="reguler">Reguler</option>
                        <option value="prestasi">Prestasi</option>
                        <option value="zona">Zonasi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jalur Pendaftaran <span class="required">*</span></label>
                    <select name="jalur_pendaftaran" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">NIK / No. KITAS <span class="required">*</span></label>
                    <input type="text" name="nik" class="form-input" placeholder="NIK / No. KITAS" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Sekolah Asal <span class="required">*</span></label>
                    <input type="text" name="sekolah_asal" class="form-input" placeholder="Nama Sekolah Asal" required>
                </div>

                <!-- Bagian 2: Data Pribadi -->
                <div class="section-title">DATA PRIBADI</div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-input" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NISN <span class="required">*</span></label>
                    <input type="text" name="nisn" class="form-input" placeholder="NISN" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir <span class="required">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-input" placeholder="Tempat Lahir" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-date" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Agama dan Kepercayaan <span class="required">*</span></label>
                    <select name="agama" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="islam">Islam</option>
                        <option value="kristen">Kristen</option>
                        <option value="katolik">Katolik</option>
                        <option value="hindu">Hindu</option>
                        <option value="budha">Budha</option>
                        <option value="konghucu">Konghucu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kewarganegaraan <span class="required">*</span></label>
                    <select name="kewarganegaraan" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="wni">WNI</option>
                        <option value="wna">WNA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Jalan <span class="required">*</span></label>
                    <textarea name="alamat_jalan" class="form-textarea" placeholder="Alamat Jalan" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Desa/Kelurahan <span class="required">*</span></label>
                    <input type="text" name="desa_kelurahan" class="form-input" placeholder="Desa/Kelurahan" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan <span class="required">*</span></label>
                    <input type="text" name="kecamatan" class="form-input" placeholder="Kecamatan" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kabupaten/Kota <span class="required">*</span></label>
                    <input type="text" name="kabupaten_kota" class="form-input" placeholder="Kabupaten/Kota" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Pos <span class="required">*</span></label>
                    <input type="text" name="kode_pos" class="form-input" placeholder="Kode Pos" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Tinggal <span class="required">*</span></label>
                    <select name="tempat_tinggal" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="bersama_orang_tua">Bersama Orang Tua</option>
                        <option value="bersama_wali">Bersama Wali</option>
                        <option value="asrama">Asrama</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Anak Ke Berapa <span class="required">*</span></label>
                    <input type="number" name="anak_ke" class="form-input" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Saudara Kandung <span class="required">*</span></label>
                    <input type="number" name="jumlah_saudara_kandung" class="form-input" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Memiliki KIP? <span class="required">*</span></label>
                    <select name="memiliki_kip" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Akan Menerima KIP? <span class="required">*</span></label>
                    <select name="akan_menerima_kip" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>

                <!-- Bagian 3: Data Ayah Kandung -->
                <div class="section-title">DATA AYAH KANDUNG</div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Ayah <span class="required">*</span></label>
                    <input type="text" name="nama_ayah" class="form-input" placeholder="Nama Lengkap Ayah" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Ayah <span class="required">*</span></label>
                    <input type="text" name="nik_ayah" class="form-input" placeholder="NIK Ayah" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir Ayah <span class="required">*</span></label>
                    <input type="text" name="tempat_lahir_ayah" class="form-input" placeholder="Tempat Lahir Ayah" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir Ayah <span class="required">*</span></label>
                    <input type="date" name="tanggal_lahir_ayah" class="form-date" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Ayah <span class="required">*</span></label>
                    <select name="pendidikan_ayah" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="sd">SD</option>
                        <option value="smp">SMP</option>
                        <option value="sma">SMA/SMK</option>
                        <option value="d1-d3">D1-D3</option>
                        <option value="s1-s3">S1-S3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Ayah <span class="required">*</span></label>
                    <select name="pekerjaan_ayah" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="pegawai_negeri">Pegawai Negeri</option>
                        <option value="swasta">Swasta</option>
                        <option value="wiraswasta">Wiraswasta</option>
                        <option value="petani">Petani</option>
                        <option value="buruh">Buruh</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Bulanan Ayah <span class="required">*</span></label>
                    <select name="penghasilan_ayah" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="kurang_1jt">&lt; Rp 1.000.000</option>
                        <option value="1jt_3jt">Rp 1.000.000 - Rp 3.000.000</option>
                        <option value="3jt_5jt">Rp 3.000.000 - Rp 5.000.000</option>
                        <option value="lebih_5jt">&gt; Rp 5.000.000</option>
                    </select>
                </div>

                <!-- Bagian 4: Data Ibu Kandung -->
                <div class="section-title">DATA IBU KANDUNG</div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Ibu <span class="required">*</span></label>
                    <input type="text" name="nama_ibu" class="form-input" placeholder="Nama Lengkap Ibu" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Ibu <span class="required">*</span></label>
                    <input type="text" name="nik_ibu" class="form-input" placeholder="NIK Ibu" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir Ibu <span class="required">*</span></label>
                    <input type="text" name="tempat_lahir_ibu" class="form-input" placeholder="Tempat Lahir Ibu" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir Ibu <span class="required">*</span></label>
                    <input type="date" name="tanggal_lahir_ibu" class="form-date" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Ibu <span class="required">*</span></label>
                    <select name="pendidikan_ibu" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="sd">SD</option>
                        <option value="smp">SMP</option>
                        <option value="sma">SMA/SMK</option>
                        <option value="d1-d3">D1-D3</option>
                        <option value="s1-s3">S1-S3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Ibu <span class="required">*</span></label>
                    <select name="pekerjaan_ibu" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="pegawai_negeri">Pegawai Negeri</option>
                        <option value="swasta">Swasta</option>
                        <option value="wiraswasta">Wiraswasta</option>
                        <option value="petani">Petani</option>
                        <option value="buruh">Buruh</option>
                        <option value="ibu_rumah_tangga">Ibu Rumah Tangga</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Bulanan Ibu <span class="required">*</span></label>
                    <select name="penghasilan_ibu" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="kurang_1jt">&lt; Rp 1.000.000</option>
                        <option value="1jt_3jt">Rp 1.000.000 - Rp 3.000.000</option>
                        <option value="3jt_5jt">Rp 3.000.000 - Rp 5.000.000</option>
                        <option value="lebih_5jt">&gt; Rp 5.000.000</option>
                    </select>
                </div>

                <!-- Bagian 5: Unggah Dokumen -->
                <div class="section-title">UNGGAH DOKUMEN</div>
                <div class="form-group">
                    <label class="form-label">Unggah Pas Foto <span class="required">*</span></label>
                    <div class="file-input-wrapper">
                        <input type="file" name="pasfoto" id="pasfoto" class="file-input" accept="image/jpeg,image/png,application/pdf" required>
                        <label for="pasfoto" class="file-label">Choose File</label>
                    </div>
                    <div class="file-note">File harus JPG/JPEG/PNG/PDF dan ukuran maksimal 0.5MB</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Unggah Kartu Keluarga <span class="required">*</span></label>
                    <div class="file-input-wrapper">
                        <input type="file" name="kk" id="kk" class="file-input" accept="image/jpeg,image/png,application/pdf" required>
                        <label for="kk" class="file-label">Choose File</label>
                    </div>
                    <div class="file-note">File harus JPG/JPEG/PNG/PDF dan ukuran maksimal 0.5MB</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Unggah Akta Lahir <span class="required">*</span></label>
                    <div class="file-input-wrapper">
                        <input type="file" name="akta" id="akta" class="file-input" accept="image/jpeg,image/png,application/pdf" required>
                        <label for="akta" class="file-label">Choose File</label>
                    </div>
                    <div class="file-note">File harus JPG/JPEG/PNG/PDF dan ukuran maksimal 0.5MB</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Unggah KIP (Opsional)</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="kip" id="kip" class="file-input" accept="image/jpeg,image/png,application/pdf">
                        <label for="kip" class="file-label">Choose File</label>
                    </div>
                    <div class="file-note">File harus JPG/JPEG/PNG/PDF dan ukuran maksimal 0.5MB</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Bukti Pembayaran (Opsional)</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="pembayaran" id="pembayaran" class="file-input" accept="image/jpeg,image/png,application/pdf">
                        <label for="pembayaran" class="file-label">Choose File</label>
                    </div>
                    <div class="file-note">File harus JPG/JPEG/PNG/PDF dan ukuran maksimal 0.5MB</div>
                </div>

                <!-- Bagian 6: CAPTCHA (Sederhana) -->
                <div class="captcha-section">
                    <label class="form-label">Masukkan kode CAPTCHA</label>
                    <div class="captcha-image" onclick="this.textContent = Math.random().toString(36).substr(2, 5).toUpperCase();">
                        2NUZ5
                    </div>
                    <input type="text" name="captcha" class="captcha-input" placeholder="Ketik kode di atas" required>
                </div>

                <!-- Persetujuan -->
                <div class="checkbox-container">
                    <input type="checkbox" id="agreement" name="agreement" required>
                    <label for="agreement">Saya menyatakan bahwa data yang saya masukkan adalah benar dan saya bertanggung jawab atas kebenarannya.</label>
                </div>

                <!-- Bagian 7: Kontak -->
                <div class="section-title">KONTAK</div>
                <div class="form-group">
                    <label class="form-label">Nomor Handphone <span class="required">*</span></label>
                    <input type="tel" name="no_hp" class="form-input" placeholder="081234567890" pattern="[0-9]{10,13}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-input" placeholder="email@domain.com" required>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="submit-btn">Daftar Sekarang</button>
            </form>
        </div>
    </div>
</body>

</html>