<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit();
}

include('../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_jabatan = mysqli_real_escape_string($koneksi, $_POST['nama_jabatan']);
    $id_departemen = mysqli_real_escape_string($koneksi, $_POST['id_departemen']);
$gaji = str_replace('.', '', $_POST['gaji']);

    // Validasi input
    if (empty($nama_jabatan) || empty($id_departemen) || empty($gaji)) {
        $_SESSION['error'] = "Semua field harus diisi!";
        header("Location: ../admin/jabatan.php");
        exit();
    }

    // Cek apakah jabatan sudah ada
    $cek_query = "SELECT * FROM jabatan WHERE nama_jabatan = '$nama_jabatan' AND id_departemen = '$id_departemen'";
    $cek_result = mysqli_query($koneksi, $cek_query);
    
    if (mysqli_num_rows($cek_result) > 0) {
        $_SESSION['error'] = "Jabatan sudah ada di departemen ini!";
        header("Location: ../admin/jabatan.php");
        exit();
    }

    // Tambah jabatan
    $query = "INSERT INTO jabatan (nama_jabatan, id_departemen, gaji) VALUES ('$nama_jabatan', '$id_departemen', '$gaji')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['success'] = "Jabatan berhasil ditambahkan!";
        header("Location: ../admin/jabatan.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan jabatan: " . mysqli_error($koneksi);
        header("Location: ../admin/jabatan.php");
        exit();
    }
} else {
    header("Location: ../admin/jabatan.php");
    exit();
}
?>