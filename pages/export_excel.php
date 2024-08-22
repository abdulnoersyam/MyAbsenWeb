<?php
session_start();
include '../auth/koneksi.php';

// Sertakan library PHPSpreadsheet
require '../libs/PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Ambil parameter bulan dan tahun dari URL
$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

// Buat objek Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menambahkan logo dan judul
// Menggabungkan sel untuk logo
$sheet->mergeCells('A1:J1'); // Menggabungkan 10 kolom untuk logo
$sheet->mergeCells('A2:J2'); // Menggabungkan 10 kolom untuk teks judul

// Mengatur gambar logo
$logo = new Drawing();
$logo->setPath('../images/logo_myabsen.png'); // Path ke gambar
$logo->setCoordinates('F1'); // Lokasi gambar di sel
$logo->setHeight(60); // Tinggi gambar
$logo->setWidth(150); // Lebar gambar
$logo->setWorksheet($sheet);
$sheet->getRowDimension(1)->setRowHeight(35);

// Mengatur teks judul
$sheet->setCellValue('A2', 'Data Presensi Guru dan Karyawan');

// Mengatur alignment, warna latar, dan border judul
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:J2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:J2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A2:J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2ea6e5'); // Warna biru
$sheet->getStyle('A2:J2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Mengatur header tabel
$sheet->setCellValue('A3', 'No')
    ->setCellValue('B3', 'Tanggal')
    ->setCellValue('C3', 'NIP')
    ->setCellValue('D3', 'Nama')
    ->setCellValue('E3', 'Posisi')
    ->setCellValue('F3', 'Absen Masuk')
    ->setCellValue('G3', 'Foto Masuk')
    ->setCellValue('H3', 'Absen Keluar')
    ->setCellValue('I3', 'Foto Keluar')
    ->setCellValue('J3', 'Status');

// Mengatur alignment, warna latar, dan border header tabel
$sheet->getStyle('A3:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3:J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2ea6e5'); // Warna biru
$sheet->getStyle('A3:J3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Query untuk mengambil data
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
        WHERE MONTH(abs.tanggal) = '$bulan' AND YEAR(abs.tanggal) = '$tahun'";
$result = $conn->query($sql);

$counter = 1;
$rowIndex = 4; // Mulai dari baris keempat setelah header

while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status'];
    if ($status == "0") {
        $statusText = "Overdue";
        $statusColor = 'FF0000'; // Merah
    } elseif ($status == "1") {
        $statusText = "On Time";
        $statusColor = '00FF00'; // Hijau
    } else {
        $statusText = "Undefined";
        $statusColor = '000000'; // Hitam atau default
    }

    $tanggalFormatted = date('d-m-Y', strtotime($row['tanggal']));

    $sheet->setCellValue('A' . $rowIndex, $counter)
        ->setCellValue('B' . $rowIndex, $tanggalFormatted)
        ->setCellValue('C' . $rowIndex, $row['nip'])
        ->setCellValue('D' . $rowIndex, $row['fullname'])
        ->setCellValue('E' . $rowIndex, $row['position'])
        ->setCellValue('F' . $rowIndex, $row['absen_masuk'])
        ->setCellValue('H' . $rowIndex, $row['absen_keluar'])
        ->setCellValue('J' . $rowIndex, $statusText);

    // Mengatur warna font berdasarkan status
    $sheet->getStyle('J' . $rowIndex)->getFont()->getColor()->setRGB($statusColor);

    // Mengatur alignment data ke tengah
    $sheet->getStyle('A' . $rowIndex . ':F' . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H' . $rowIndex . ':J' . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A' . $rowIndex . ':F' . $rowIndex)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('H' . $rowIndex . ':J' . $rowIndex)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    // Menambahkan gambar absen masuk
    if (file_exists('../images/' . $row['foto_masuk'])) {
        $drawing = new Drawing();
        $drawing->setPath('../images/' . $row['foto_masuk']); // Path ke gambar
        $drawing->setCoordinates('G' . $rowIndex); // Lokasi gambar di sel
        $drawing->setHeight(50); // Tinggi gambar
        $drawing->setWidth(100); // Lebar gambar
        $drawing->setWorksheet($sheet);
        // Set tinggi baris sesuai tinggi gambar
        $sheet->getRowDimension($rowIndex)->setRowHeight(135);
    }

    // Menambahkan gambar absen keluar
    if (file_exists('../images/' . $row['foto_keluar'])) {
        $drawing = new Drawing();
        $drawing->setPath('../images/' . $row['foto_keluar']); // Path ke gambar
        $drawing->setCoordinates('I' . $rowIndex); // Lokasi gambar di sel
        $drawing->setHeight(50); // Tinggi gambar
        $drawing->setWidth(100); // Lebar gambar
        $drawing->setWorksheet($sheet);
        // Set tinggi baris sesuai tinggi gambar
        $sheet->getRowDimension($rowIndex)->setRowHeight(135);
    }

    $counter++;
    $rowIndex++;
}

// Mengatur border pada semua sel data
$sheet->getStyle('A3:J' . ($rowIndex - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Mengatur lebar kolom gambar agar sesuai dengan gambar
$sheet->getColumnDimension('G')->setWidth(14);
$sheet->getColumnDimension('I')->setWidth(14);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(15);

// Menulis file ke output
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Presensi_Guru_dan_Karyawan_' . $bulan . '_' . $tahun . '.xlsx"');
$writer->save('php://output');
exit;
?>
