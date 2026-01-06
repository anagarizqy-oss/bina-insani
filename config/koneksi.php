<?php
$host = 'localhost';
$dbname = 'sma_bina_insani';
$user = 'root';
$pass = '';
$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}
$sql = "
SELECT * FROM siswa";
$result = $db->query($sql);
