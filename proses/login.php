<?php
session_start();
include('../config/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        echo "<script>
                alert('Username atau password salah!');
                window.location.href='../admin/login.php';
              </script>";
    }
}
?>