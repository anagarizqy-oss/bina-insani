<nav class="navbar-new">
    <div class="nav-left">
    <a href="index.php">Beranda</a>

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
    <a href="#info">Informasi</a>
    <a href="#galeri">Galeri</a>
    <a href="masukan.php">Masukan & Saran</a>
    <a href="kontak.php">Kontak</a>
</div>
</nav>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    
    // Tutup dropdown lain
    document.querySelectorAll('.dropdown-content').forEach(el => {
        if (el.id !== id) el.classList.remove('show');
    });

    // Toggle class show
    dropdown.classList.toggle('show');
}

// Klik di luar untuk menutup
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        document.querySelectorAll('.dropdown-content').forEach(el => {
            el.classList.remove('show');
        });
    }
}
</script>