<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit();
}

include('../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pegawai = mysqli_real_escape_string($koneksi, $_POST['id_pegawai']);
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

    // Cek apakah NIP sudah ada di pegawai lain
    $cek_nip_query = "SELECT * FROM pegawai WHERE nip = '$nip' AND id_pegawai != '$id_pegawai'";
    $cek_nip_result = mysqli_query($koneksi, $cek_nip_query);
    
    if (mysqli_num_rows($cek_nip_result) > 0) {
        $_SESSION['error'] = "NIP sudah terdaftar!";
        header("Location: ../admin/pegawai.php");
        exit();
    }

    // Cek apakah email sudah ada di pegawai lain
    $cek_email_query = "SELECT * FROM pegawai WHERE email = '$email' AND id_pegawai != '$id_pegawai'";
    $cek_email_result = mysqli_query($koneksi, $cek_email_query);
    
    if (mysqli_num_rows($cek_email_result) > 0) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        header("Location: ../admin/pegawai.php");
        exit();
    }

    // Update pegawai
    $query = "UPDATE pegawai 
    SET nip = '$nip', 
    nama = '$nama', 
    email = '$email', 
    jenis_kelamin = '$jenis_kelamin', 
    id_jabatan = '$id_jabatan', 
    id_departemen = '$id_departemen', 
    gaji = '$gaji' 
    WHERE id_pegawai = '$id_pegawai'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['success'] = "Pegawai berhasil diperbarui!";
        header("Location: ../admin/pegawai.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui pegawai: " . mysqli_error($koneksi);
        header("Location: ../admin/pegawai.php");
        exit();
    }
} else {
    header("Location: ../admin/pegawai.php");
    exit();
}
?>