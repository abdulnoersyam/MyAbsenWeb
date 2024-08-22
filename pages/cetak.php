<?php
include 'auth/koneksi.php';

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_absen_{$bulan}_{$tahun}.xls");

echo "No\tTanggal\tNIP\tNama\tPosisi\tAbsen Masuk\tFoto Masuk\tAbsen Keluar\tFoto Keluar\tStatus\n";

$sql = "SELECT 
        abs.tanggal, 
        abs.absen_masuk, 
        abs.absen_keluar, 
        abs.foto_masuk, 
        abs.foto_keluar, 
        abs.status, 
        abs.nip,
        user.fullname, 
        user.position  
        FROM absen AS abs 
        INNER JOIN guru_dan_karyawan AS user
        ON abs.nip = user.nip
        WHERE MONTH(abs.tanggal) = ? AND YEAR(abs.tanggal) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $bulan, $tahun);
$stmt->execute();
$result = $stmt->get_result();

$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status'];
    if ($status == "0") {
        $statusText = "Overdue";
    } elseif ($status == "1") {
        $statusText = "On Time";
    } else {
        $statusText = "Undefined";
    }

    $tanggalFormatted = date('d-m-Y', strtotime($row['tanggal']));

    echo "{$counter}\t{$tanggalFormatted}\t{$row['nip']}\t{$row['fullname']}\t{$row['position']}\t{$row['absen_masuk']}\t{$row['foto_masuk']}\t{$row['absen_keluar']}\t{$row['foto_keluar']}\t{$statusText}\n";
    $counter++;
}
