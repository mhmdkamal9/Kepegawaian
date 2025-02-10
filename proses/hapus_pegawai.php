<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit();
}

include('../config/koneksi.php');

if (isset($_GET['id'])) {
    $id_pegawai = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Hapus pegawai
    $query = "DELETE FROM pegawai WHERE id_pegawai = '$id_pegawai'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['success'] = "Pegawai berhasil dihapus!";
        header("Location: ../admin/pegawai.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menghapus pegawai: " . mysqli_error($koneksi);
        header("Location: ../admin/pegawai.php");
        exit();
    }
} else {
    header("Location: ../admin/pegawai.php");
    exit();
}
?>