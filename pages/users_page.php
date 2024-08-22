<?php
include 'auth/koneksi.php';

$sql = "SELECT * FROM guru_dan_karyawan";
$result = mysqli_query($conn, $sql);

function maskPassword($password)
{
    $length = strlen($password);
    if ($length <= 2) {
        return $password;
    }

    $firstChar = $password[0];
    $lastChar = $password[$length - 1];
    $masked = str_repeat('*', $length - 2);

    return $firstChar . $masked . $lastChar;
}

?>

<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h3">Data Guru dan Karyawan</h1>
    <p>Data guru dan karyawan di aplikasi My Absen</p>
    <div class="row">
        <div class="col mb-4 mb-lg-0">
            <div class="card">
                <form method="GET" action="search.php">
                    <div class="card-header input-group pt-3 pb-3 mb-3">
                        <input type="hidden" name="table" value="user">
                        <input type="text" class="form-control" placeholder="Cari data" aria-label="Recipient's username" aria-describedby="basic-addon2" name="query" id="search" onkeyup="searchUser()">
                        <span class="input-group-text" id="basic-addon2">Cari</span>
                    </div>
                </form>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama lengkap</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Password</th>
                                </tr>
                            </thead>
                            <tbody id="anggota-list">
                                <?php
                                $counter = 1;

                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <th><?php echo $counter; ?></th>
                                        <td><?php echo $row['nip']; ?></td>
                                        <td><?php echo $row['fullname']; ?></td>
                                        <td><?php echo $row['position']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                            <?php
                                            $passwordFromDB = $row['password'];
                                            $maskedPassword = maskPassword($passwordFromDB);
                                            echo $maskedPassword;
                                            ?>
                                        </td>
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
        <span>Copyright © 2024 My Absen </a></span>
    </footer>
</main>
