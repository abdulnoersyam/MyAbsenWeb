<?php
include 'auth/koneksi.php';

$sql = "SELECT COUNT(*) FROM guru_dan_karyawan";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($result)[0];

$sql2 = "SELECT COUNT(*) FROM absen";
$result2 = mysqli_query($conn, $sql2);
$absen = mysqli_fetch_array($result2)[0];
?>

<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Dashboard</h1>
    <p>Selamat datang di dashboard admin!</p>

    <div class="row">
        <div class="row pt-2 px-2">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h4><span class="fas fa-user-tie mr-2"></span>Jumlah User</h4>
                        <h4 class="mt-3"><?php echo $user ?></h4>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="?page=users_page">Lihat Selengkapnya</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h4><span class="fas fa-file mr-2"></span>Jumlah Absen</h4>
                        <h4 class="mt-3"><?php echo $absen ?></h4>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="?page=absen_page">Lihat Selengkapnya</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>