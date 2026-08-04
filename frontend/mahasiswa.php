<?php

    require "fungsi.php";

    if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true)
    {
        header("Location: login.php");
        exit;
    }

    $qmahasiswa = "SELECT * FROM mahasiswa";
    $mahasiswas = tampildata($qmahasiswa);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa | Informatika</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            padding-bottom: 50px;
        }

        /* Top Bar Header */
        .top-bar {
            background-color: #ffffff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .top-bar h1 {
            font-size: 20px;
            color: #4f46e5;
            font-weight: 700;
        }

        .user-nav {
            font-size: 14px;
            color: #4b5563;
        }

        .user-nav a {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Modern Navigation Bar */
        .nav-menu {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 8px 32px;
            display: flex;
            gap: 12px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #4b5563;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background-color: #eeef2;
            color: #4f46e5;
        }

        /* Main Container */
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 22px;
            color: #111827;
        }

        .btn-add {
            background-color: #4f46e5;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover {
            background-color: #4338ca;
        }

        /* Table Card */
        .card-table {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 40px;
        }

        table.custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table.custom-table th {
            background-color: #4f46e5;
            color: white;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
            vertical-align: middle;
        }

        table.custom-table tr:hover {
            background-color: #f9fafb;
        }

        .img-thumb {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
        }

        /* Buttons Action */
        .btn-action {
            padding: 6px 14px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            color: white;
            transition: opacity 0.2s;
            margin-right: 4px;
        }

        .btn-edit {
            background-color: #f59e0b;
        }

        .btn-delete {
            background-color: #ef4444;
        }

        .btn-action:hover {
            opacity: 0.85;
        }

        /* Style Table Matriks Tambahan */
        .card-matrix {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: inline-block;
        }

        table.matrix-table {
            border-collapse: collapse;
        }

        table.matrix-table td {
            border: 1px solid #e5e7eb;
            padding: 12px 18px;
            text-align: center;
            font-weight: 600;
            color: #4b5563;
        }
    </style>
</head>
<body>

    <!-- Header Atas -->
    <div class="top-bar">
        <h1>INFORMATIKA 2026</h1>
        <div class="user-nav">
            Halo, <b><?= htmlspecialchars($_SESSION["username"]) ?></b>! | <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Navigasi Menu -->
    <div class="nav-menu">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="contact.php">Contact</a>
        <a href="mahasiswa.php" class="active">Mahasiswa</a>
        <a href="register.php">Register</a>
    </div>

    <div class="container">
        
        <div class="section-header">
            <h2>Data Mahasiswa</h2>
            <a href="tambahdata.php">
                <button class="btn-add">+ Tambah Data</button>
            </a>
        </div>

        <!-- Tabel Utama Data Mahasiswa -->
        <div class="card-table">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 50px;">No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Email</th>
                        <th>No. Hp</th>
                        <th style="text-align: center;">Foto</th>
                        <th style="text-align: center; width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                    $i = 1;
                    foreach ($mahasiswas as $mhs):
                   ?>
                    <tr>
                        <td style="text-align: center; font-weight: 600;"><?= $i++; ?></td>
                        <td><b><?= htmlspecialchars($mhs["nama"]) ?></b></td>
                        <td><?= htmlspecialchars($mhs["nim"]) ?></td>
                        <td><?= htmlspecialchars($mhs["jurusan"]) ?></td>
                        <td><?= htmlspecialchars($mhs["email"]) ?></td>
                        <td><?= htmlspecialchars($mhs["no_hp"]) ?></td>
                        <td style="text-align: center;">
                            <img src="../assets/Image/<?= $mhs["foto"] ?>" class="img-thumb" alt="Foto">
                        </td>
                        <td style="text-align: center;">
                            <a href="editdata.php"><button class="btn-action btn-edit">Edit</button></a>
                            <a href="hapusdata.php?id=<?= $mhs["id"]; ?>" onclick="return confirm('Yakin ingin ngehapus ini??');"><button class="btn-action btn-delete">Hapus</button></a>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Matriks/Grid Tambahan -->
        <div class="card-matrix">
            <h4 style="margin-bottom: 10px; color: #6b7280;">Layout Grid Matrix</h4>
            <table class="matrix-table">
                <tr>
                    <td>1,1</td>
                    <td>1,2</td>
                    <td>1,3</td>
                    <td>1,4</td>
                </tr>
                <tr>
                    <td>2,1</td>
                    <td rowspan="2" colspan="2" style="background-color: #f3f4f6; font-size: 20px; color: #4f46e5;">?</td>
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
        </div>

    </div>

</body>
</html>