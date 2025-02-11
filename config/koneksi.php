<?php
$host = "localhost";
$user = "root";  
$pass = "";     
$db   = "kamal_if10_kepegawaian";

// Buat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
