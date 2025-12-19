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
                <a href="agenda-kegiatan.php">Agenda Kegiatan</a>
                <a href="kalender-akademik.php">Kalender Akademik</a>
                <a href="jadwal-uji.php">Jadwal Ujian</a>
                <a href="libur-nasional.php">Libur Nasional</a>
            </div>
        </div>

        <a href="ekstrakurikuler.php">Ekstrakurikuler</a>
        <a href="info.php">Informasi</a>
        <a href="index.php#galeri">Galeri</a>

        <!-- ✅ DIUBAH DI SINI -->
 <a href="masukan.php">Masukan & Saran</a>

        <a href="kontak.php">Kontak</a>
    </div>

    <div class="nav-right">
        <a href="login.php" class="btn-login-nav">Login Akun</a>
        <a href="#" class="btn-ppdb">PPDB</a>
    </div>
</nav>

<script>
    function toggleDropdown(id) {
        document.querySelectorAll('.dropdown-content').forEach(el => {
            if (el.id !== id) {
                el.style.display = 'none';
            }
        });

        const dropdown = document.getElementById(id);
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
</script>
