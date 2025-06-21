<?php
session_start();
require 'koneksi.php';

if(empty($_SESSION['noHp'])) {
    header('location:login.php?message=belum_login');
    exit();
}
$idPesanan = $_GET['id'];

$noHp = $_SESSION['noHp'];
$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$query = mysqli_query($connect, $sql);
$data = mysqli_fetch_array($query);
$name = $data['namaPelanggan'];

$sql2 = "SELECT * FROM pesanan WHERE idPesanan = '$idPesanan'";
$query2 = mysqli_query($connect, $sql2);
$data2 = mysqli_fetch_array($query2);
$jenisPengiriman = $data2['jenisPengiriman'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <style>
        :root {
            --primary: #3D4EB0;
            --text-dark: #222;
            --text-light: #777;
            --background: #f9f9fc;
            --button-bg: #3D4EB0;
            --button-text: #fff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 30px 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .close-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #f1f1f1;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        h2 {
            font-size: 1.4rem;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        p {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 30px;
        }

        .progress-circle {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 30px auto;
        }

        .progress-circle svg {
            transform: rotate(-90deg);
        }

        .progress-circle circle {
            fill: none;
            stroke-width: 12;
        }

        .progress-bg {
            stroke: #eee;
        }

        .progress-bar {
            stroke: var(--primary);
            stroke-dasharray: 314;
            stroke-dashoffset: calc(314 - (314 * 50 / 100)); /* 50% */
            transition: stroke-dashoffset 1s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: 1rem;
            color: var(--text-light);
        }

        .illustration {
            width: 100px;
            height: auto;
            margin-bottom: 30px;
        }

        .btn {
            background-color: var(--button-bg);
            color: var(--button-text);
            border: none;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #2f3e90;
        }

        @media (max-width: 480px) {
            .container {
                border-radius: 0;
                height: 100vh;
                justify-content: center;
            }
            .close-button {
                top: 15px;
                right: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="close-button" onclick="window.location.href='dashboard.php'">&times;</button>
        <?php 
        //var_dump($jenisPengiriman);
        if($jenisPengiriman == 'Ambil sendiri') { ?>
            <h2>Pesanan Berhasil Dibuat!</h2>
            <p>Sekarang tinggal antar pakaianmu ke kasir di toko laundry, ya!</p>
        <?php } 
        else if($jenisPengiriman == 'Kurir') { ?>
            <h2>Pesanan Berhasil Dibuat!</h2>
            <p>Tunggu sebentar ya, kurir kami bakal datang jemput cucianmu</p>
        <?php } ?>

        <div class="progress-circle">
            <svg width="120" height="120">
                <circle class="progress-bg" cx="60" cy="60" r="50"></circle>
                <circle class="progress-bar" cx="60" cy="60" r="50"></circle>
            </svg>
            <div class="progress-text">Completed<br>50%</div>
        </div>

        <!-- Gambar ilustrasi -->
        <img src="image/sukses.png" alt="Ilustrasi" class="illustration">

        <!-- Tombol -->
        <button class="btn" onclick="window.location.href='dashboard.php'">Place New order</button>
    </div>
</body>
</html>
