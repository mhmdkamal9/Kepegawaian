<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit();
}

include('../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $id_jabatan = mysqli_real_escape_string($koneksi, $_POST['id_jabatan']);
    $id_departemen = mysqli_real_escape_string($koneksi, $_POST['id_departemen']);
    $gaji = str_replace('.', '', $_POST['gaji']);

    // Validasi input
    if (empty($nip) || empty($nama) || empty($email) || empty($jenis_kelamin) || 
        empty($id_jabatan) || empty($id_departemen) || empty($gaji)) {
        $_SESSION['error'] = "Semua field harus diisi!";
        header("Location: ../admin/pegawai.php");
        exit();
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid!";
        header("Location: ../admin/pegawai.php");
        exit();
    }

    // Cek apakah NIP sudah ada
    $cek_nip_query = "SELECT * FROM pegawai WHERE nip = '$nip'";
    $cek_nip_result = mysqli_query($koneksi, $cek_nip_query);
    
    if (mysqli_num_rows($cek_nip_result) > 0) {
        $_SESSION['error'] = "NIP sudah terdaftar!";
        header("Location: ../admin/pegawai.php");
        exit();
    }

    // Cek apakah email sudah ada
    $cek_email_query = "SELECT * FROM pegawai WHERE email = '$email'";
    $cek_email_result = mysqli_query($koneksi, $cek_email_query);
    
    if (mysqli_num_rows($cek_email_result) > 0) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        header("Location: ../admin/pegawai.php");
        exit();
    }

    // Tambah pegawai
    $query = "INSERT INTO pegawai (nip, nama, email, jenis_kelamin, id_jabatan, id_departemen, gaji) 
            VALUES ('$nip', '$nama', '$email', '$jenis_kelamin', '$id_jabatan', '$id_departemen', '$gaji')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['success'] = "Pegawai berhasil ditambahkan!";
        header("Location: ../admin/pegawai.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan pegawai: " . mysqli_error($koneksi);
        header("Location: ../admin/pegawai.php");
        exit();
    }
} else {
    header("Location: ../admin/pegawai.php");
    exit();
}
?>