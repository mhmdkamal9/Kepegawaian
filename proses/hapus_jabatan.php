<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit();
}

include('../config/koneksi.php');

if (isset($_GET['id'])) {
    $id_jabatan = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Debug: Tampilkan ID jabatan yang akan dihapus
    error_log("Mencoba menghapus jabatan dengan ID: $id_jabatan");

    // Cek apakah jabatan memiliki pegawai
    $cek_pegawai_query = "SELECT * FROM pegawai WHERE id_jabatan = '$id_jabatan'";
    $cek_pegawai_result = mysqli_query($koneksi, $cek_pegawai_query);
    
    // Debug: Tampilkan jumlah pegawai yang terkait
    $jumlah_pegawai = mysqli_num_rows($cek_pegawai_result);
    error_log("Jumlah pegawai dengan jabatan ini: $jumlah_pegawai");

    if ($jumlah_pegawai > 0) {
        // Debug: Tampilkan detail pegawai
        while ($pegawai = mysqli_fetch_assoc($cek_pegawai_result)) {
            error_log("Pegawai terkait: " . print_r($pegawai, true));
        }

        $_SESSION['error'] = "Tidak dapat menghapus jabatan yang masih memiliki pegawai!";
        header("Location: ../admin/jabatan.php");
        exit();
    }

    // Hapus jabatan
    $query = "DELETE FROM jabatan WHERE id_jabatan = '$id_jabatan'";
    $result = mysqli_query($koneksi, $query);

    // Debug: Tampilkan hasil query
    if ($result) {
        error_log("Jabatan berhasil dihapus");
        $_SESSION['success'] = "Jabatan berhasil dihapus!";
        header("Location: ../admin/jabatan.php");
        exit();
    } else {
        error_log("Gagal menghapus jabatan: " . mysqli_error($koneksi));
        $_SESSION['error'] = "Gagal menghapus jabatan: " . mysqli_error($koneksi);
        header("Location: ../admin/jabatan.php");
        exit();
    }
} else {
    header("Location: ../admin/jabatan.php");
    exit();
}
?>