<?php
include '../auth/koneksi.php';

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

$query = isset($_GET['query']) ? $_GET['query'] : '';
$table = isset($_GET['table']) ? $_GET['table'] : '';

if ($table == 'user') {
    $sql = $query ? "SELECT * FROM guru_dan_karyawan WHERE fullname LIKE '%$query%' OR nip LIKE '%$query%' OR position LIKE '%$query%' OR email LIKE '%$query%'" : "SELECT * FROM guru_dan_karyawan";
} else {
    echo "Invalid table";
    exit();
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($table == 'user') {
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
    }
} else {
    ?>
    <tr>
        <td colspan="7">Data tidak ditemukan.</td>
    </tr>
<?php
}
?>