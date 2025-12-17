<nav class="navbar-new">
    <div class="nav-left">
    <a href="/sma-bina-insani/index.php">Beranda</a>

    <div class="dropdown">
        <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('profil-kami')">
            Profil Kami ▾
        </a>
        <div id="profil-kami" class="dropdown-content">
            <a href="/sma-bina-insani/profil-sekolah.php">Profil Sekolah</a>
            <a href="/sma-bina-insani/identitas.php">Identitas Sekolah</a>
            <a href="/sma-bina-insani/visimisi.php">Visi & Misi</a>
            <a href="/sma-bina-insani/sejarah.php">Sejarah Singkat</a>
            <a href="/sma-bina-insani/struktur.php">Struktur Organisasi</a>
            <a href="/sma-bina-insani/fasilitas.php">Fasilitas</a>
            <a href="/sma-bina-insani/staf-pengajar.php">Staf Pengajar</a>
            <a href="/sma-bina-insani/tenaga-kependidikan.php">Staf Tenaga Kependidikan</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown('agenda')">
            Agenda ▾
        </a>
        <div id="agenda" class="dropdown-content">
            <a href="/sma-bina-insani/agenda-kegiatan.php">Agenda Kegiatan</a>
            <a href="/sma-bina-insani/kalender-akademik.php">Kalender Akademik</a>
            <a href="/sma-bina-insani/jadwal-uji.php">Jadwal Ujian</a>
            <a href="/sma-bina-insani/libur-nasional.php">Libur Nasional</a>
        </div>
    </div>

    <a href="/sma-bina-insani/ekskul/ekstrakurikuler.php">Ekstrakurikuler</a>
    <a href="#info">Informasi</a>
    <a href="#galeri">Galeri</a>
    <a href="#masukan">Masukan & Saran</a>
    <a href="/sma-bina-insani/kontak.php">Kontak</a>
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