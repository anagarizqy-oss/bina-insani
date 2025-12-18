<?php
// includes/navbar.php
?>
<nav class="navbar-new">
    <div class="nav-left">
        <a href="index.php">Beranda</a>

        <!-- PROFIL KAMI -->
        <div class="dropdown">
            <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('profil-kami')">
                Profil Kami ▾
            </a>
            <div id="profil-kami" class="dropdown-content">
                <a href="profil-sekolah.php">Profil Sekolah</a>
                <a href="identitas.php">Identitas Sekolah</a>
                <a href="visimisi.php">Visi & Misi</a>
                <a href="sejarah.php">Sejarah Singkat</a>
                <a href="struktur.php">Struktur Organisasi</a>
                <a href="fasilitas.php">Fasilitas</a>
                <a href="staf-pengajar.php">Staf Pengajar</a>
                <a href="tenaga-kependidikan.php">Staf Tenaga Kependidikan</a>
            </div>
        </div>

        <!-- AGENDA -->
        <div class="dropdown">
            <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('agenda')">
                Agenda ▾
            </a>
            <div id="agenda" class="dropdown-content">
                <a href="#agenda-kegiatan">Agenda Kegiatan</a>
                <a href="#kalender-akademik">Kalender Akademik</a>
                <a href="#jadwal-uji">Jadwal Ujian</a>
                <a href="#libur-nasional">Libur Nasional</a>
            </div>
        </div>

        <a href="ekstrakurikuler.php">Ekstrakurikuler</a>
        <a href="informasi.php">Informasi</a>
        <a href="#galeri">Galeri</a>
        <a href="#masukan">Masukan & Saran</a>
        <a href="kontak.php">Kontak</a>
    </div>
    <div class="nav-right">
        <a href="login.php" class="btn-login-nav">Login Akun</a>
        <a href="#" class="btn-ppdb">PPDB</a>
    </div>
</nav>

<script>
    function toggleDropdown(id) {
        // Tutup semua dropdown dulu
        document.querySelectorAll('.dropdown-content').forEach(el => {
            if (el.id !== id) {
                el.style.display = 'none';
            }
        });

        // Toggle dropdown yang diklik
        const dropdown = document.getElementById(id);
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
</script>