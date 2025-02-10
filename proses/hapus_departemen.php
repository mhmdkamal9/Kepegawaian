<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit();
}

include('../config/koneksi.php');

if (isset($_GET['id'])) {
    $id_departemen = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Cek apakah departemen memiliki pegawai atau jabatan
    $cek_pegawai_query = "SELECT * FROM pegawai WHERE id_departemen = '$id_departemen'";
    $cek_pegawai_result = mysqli_query($koneksi, $cek_pegawai_query);

    $cek_jabatan_query = "SELECT * FROM jabatan WHERE id_departemen = '$id_departemen'";
    $cek_jabatan_result = mysqli_query($koneksi, $cek_jabatan_query);
    
    if (mysqli_num_rows($cek_pegawai_result) > 0 || mysqli_num_rows($cek_jabatan_result) > 0) {
        $_SESSION['error'] = "Tidak dapat menghapus departemen yang masih memiliki pegawai atau jabatan!";
        header("Location: ../admin/departemen.php");
        exit();
    }

    // Hapus departemen
    $query = "DELETE FROM departemen WHERE id_departemen = '$id_departemen'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['success'] = "Departemen berhasil dihapus!";
        header("Location: ../admin/departemen.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menghapus departemen: " . mysqli_error($koneksi);
        header("Location: ../admin/departemen.php");
        exit();
    }
} else {
    header("Location: ../admin/departemen.php");
    exit();
}
?>