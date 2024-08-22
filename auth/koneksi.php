<?php
$host = 'localhost';
$dbName = 'myabsenm_myabsen';
$user = 'myabsenm_syam';
$password = 'melsa789_';

$conn = mysqli_connect($host, $user, $password, $dbName);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}