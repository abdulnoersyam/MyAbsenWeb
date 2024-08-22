<?php
session_start();

include 'koneksi.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM sekretaris WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $_SESSION['id_sekretaris'] = $row['id_sekretaris'];
        header('Location: ../dashboard.php');
        exit();
    } else {
        $_SESSION['login_error'] = "Username atau password salah!";
        header('Location: ../index.php');
        exit();
    }
}
