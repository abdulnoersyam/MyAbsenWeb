<?php
include 'auth/koneksi.php';

// Ambil nilai bulan dan tahun dari form
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

// Tambahkan filter ke query SQL
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

$result = mysqli_query($conn, $sql);
?>

<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
    <div class="row">
        <div class="col">
            <h1 class="h2">Data Absen</h1>
            <p>Data absen guru dan karyawan di aplikasi My Absen</p>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Filter Bulan dan Tahun
                </div>
                <div class="card-body">
                    <form method="POST" action="" class="form-inline">
                        <div class="row align-items-end">
                            <div class="form-group col-md-4">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="01" <?php if ($bulan == '01') echo 'selected'; ?>>Januari</option>
                                    <option value="02" <?php if ($bulan == '02') echo 'selected'; ?>>Februari</option>
                                    <option value="03" <?php if ($bulan == '03') echo 'selected'; ?>>Maret</option>
                                    <option value="04" <?php if ($bulan == '04') echo 'selected'; ?>>April</option>
                                    <option value="05" <?php if ($bulan == '05') echo 'selected'; ?>>Mei</option>
                                    <option value="06" <?php if ($bulan == '06') echo 'selected'; ?>>Juni</option>
                                    <option value="07" <?php if ($bulan == '07') echo 'selected'; ?>>Juli</option>
                                    <option value="08" <?php if ($bulan == '08') echo 'selected'; ?>>Agustus</option>
                                    <option value="09" <?php if ($bulan == '09') echo 'selected'; ?>>September</option>
                                    <option value="10" <?php if ($bulan == '10') echo 'selected'; ?>>Oktober</option>
                                    <option value="11" <?php if ($bulan == '11') echo 'selected'; ?>>November</option>
                                    <option value="12" <?php if ($bulan == '12') echo 'selected'; ?>>Desember</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="tahun">Tahun</label>
                                <select name="tahun" id="tahun" class="form-control">
                                    <?php
                                    $currentYear = date('Y');
                                    for ($i = 2024; $i <= $currentYear; $i++) {
                                        $selected = ($tahun == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-end pt-3">
                            <button class="btn btn-primary mr-2" type="submit">Filter</button>
                            <button class="btn btn-success" type="button" onclick="window.location.href='pages/export_excel.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>'">Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col mb-4 mb-lg-0">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Posisi</th>
                                    <th scope="col">Absen Masuk</th>
                                    <th scope="col">Foto Masuk</th>
                                    <th scope="col">Absen Keluar</th>
                                    <th scope="col">Foto Keluar</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody id="anggota-list">
                                <?php
                                $counter = 1;

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $status = $row['status'];
                                    if ($status == "0") {
                                        $statusText = "Overdue";
                                        $statusColor = "red";
                                    } elseif ($status == "1") {
                                        $statusText = "On Time";
                                        $statusColor = "green";
                                    } else {
                                        $statusText = "Undefined";
                                        $statusColor = "black";
                                    }

                                    $tanggalFormatted = date('d-m-Y', strtotime($row['tanggal']));
                                ?>
                                    <tr>
                                        <th><?php echo $counter; ?></th>
                                        <td><?php echo $tanggalFormatted ?></td>
                                        <td><?php echo $row['nip']; ?></td>
                                        <td><?php echo $row['fullname']; ?></td>
                                        <td><?php echo $row['position']; ?></td>
                                        <td><?php echo $row['absen_masuk']; ?></td>
                                        <td>
                                            <img src="images/<?php echo $row['foto_masuk']; ?>" alt="img" style="width: 50px">
                                        </td>
                                        <td><?php echo $row['absen_keluar']; ?></td>
                                        <td>
                                            <img src="images/<?php echo $row['foto_keluar']; ?>" alt="img" style="width: 50px">
                                        </td>
                                        <td style="color: <?php echo $statusColor; ?>"><?php echo $statusText; ?></td>
                                    </tr>
                                <?php
                                    $counter++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="pt-5 d-flex justify-content-between">
        <span>Copyright © 2024 My Absen</span>
    </footer>
</main>