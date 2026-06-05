<?php

    require "fungsi.php";
    $qmahasiswa = "SELECT * FROM mahasiswa";
    $mahasiswas = tampildata($qmahasiswa);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa | Informatika</title>
</head>
<body>

    <h1>INFORMATIKA 2026</h1>

    <table border="1" cellspacing="0" cellpadding="10">
        <tr>
            <th><a href="index.php">Home</a></th>
            <th><a href="profile.php">Profile</a></th>
            <th><a href="contact.php">Contact</a></th>
            <th><a href="mahasiswa.php">Mahasiswa</a></th>
        </tr>
    </table>

    <br>
    <hr>

    <h2>Data Mahasiswa</h2>

    <a href="tambahdata.php">
        <button>Tambah Data</button>
    </a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th>Email</th>
            <th>No. Hp</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
       <?php
        $i = 1;
        foreach ($mahasiswas as $mhs):
        ?>

    <tr>
        <td align="center"><?= $i++; ?></td>
        <td><?= $mhs["nama"] ?></td>
        <td><?= $mhs["nim"] ?></td>
        <td><?= $mhs["jurusan"] ?></td>
        <td><?= $mhs["email"] ?></td>
        <td><?= $mhs["no_hp"] ?></td>
        <td>
            <img src="../assets/Image/<?= $mhs["foto"] ?>" width="60">
            </td>
        <td>
            <a href="editdata.php"><button>Edit</button></a>
            <a href="deletedata.php"><button>Hapus</button></a>
        </td>
    </tr>

    <?php 
        endforeach; 
    ?>
    </table>

    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <td>1,1</td>
            <td>1,2</td>
            <td>1,3</td>
            <td>1,4</td>
        </tr>

        <tr>
            <td>2,1</td>
            <td align="center" rowspan="2" colspan="2">?</td>
            <td>2,4</td>
        </tr>

        <tr>
            <td>3,1</td>
            <td>3,4</td>
        </tr>

        <tr>
            <td>4,1</td>
            <td>4,2</td>
            <td>4,3</td>
            <td>4,4</td>
        </tr>
    </table>

</body>
</html>