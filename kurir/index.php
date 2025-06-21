<?php
session_start();
if (!isset($_SESSION['idKurir'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
include 'get_nama_kurir.php';

// Ambil nama kurir dari file
$namaKurir = include('get_nama_kurir.php');

// Ambil jumlah penjemputan dengan status 'Diproses'
$queryJemput = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM penjemputan WHERE statusJemput = 'Diproses'");
$dataJemput = mysqli_fetch_assoc($queryJemput);
$jumlah_jemput = $dataJemput['jumlah'];

// Ambil jumlah pengantaran dengan status 'Diproses'
$queryAntar = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pengantaran WHERE statusAntar = 'Diproses'");
$dataAntar = mysqli_fetch_assoc($queryAntar);
$jumlah_antar = $dataAntar['jumlah'];

// $idKurir = $_SESSION['idKurir'];
// $queryJemput = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM penjemputan WHERE status = 'Diproses' AND id_kurir = '$idKurir'");
// $queryAntar  = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM pengantaran WHERE status = 'Diproses' AND id_kurir = '$idKurir'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
        }

        .header {
            background-color: #FFD700;
            padding: 1.5rem;
            color: #000;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            position: relative;
            text-align: center;
        }

        .welcome {
            margin-top: 1rem;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .status-container {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .status-card {
            background-color: #fff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;       /* penuh dalam wrapper */
            height: 120px;     /* tinggi tetap */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .status-card h2 {
            color: red;
            margin: 0;
            font-size: 2rem;
        }

        .status-card p {
            font-weight: bold;
            margin: 0.5rem 0 0;
        }

        .status-link {
            flex: 1;             /* supaya link / card bagian sama */
            max-width: 160px;    /* batas maksimal lebar agar tidak terlalu besar */
            text-decoration: none;
            color: inherit;
        }

        .status-link:hover .status-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
            transition: 0.3s;
        }

        .main-image {
            text-align: center;
            margin-top: 6rem;
        }

        .main-image img {
            width: 250px;
            opacity: 0.2;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            background-color: #fff;
            padding: 0.5rem 0;
            border-top: 1px solid #ccc;
        }

        .nav-item {
            text-align: center;
            font-size: 0.85rem;
            color: #777;
        }

        .nav-item img {
            width: 24px;
            height: 24px;
        }

        .nav-link {
        text-decoration: none;
        color: black;
        }


        @media (max-width: 480px) {
            .status-card {
                width: 120px;
                padding: 0.8rem;
            }

            .status-card h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="welcome">Selamat datang, <?php echo htmlspecialchars($namaKurir); ?>!</div>
        <div class="status-container">
            <a href="penjemputan.php" class="status-link">
                <div class="status-card">
                    <h2><?php echo $jumlah_jemput; ?></h2>
                    <p>Perlu dijemput</p>
                </div>
            </a>
            <a href="pengantaran.php" class="status-link">
                <div class="status-card">
                    <h2><?php echo $jumlah_antar; ?></h2>
                    <p>Perlu diantar</p>
                </div>
            </a>
        </div>
    </div>

    <div class="main-image">
        <img src="https://img.icons8.com/?size=100&id=111605&format=png&color=000000" alt="Ilustrasi truk dan paket">
    </div>

    <div class="bottom-nav">
        <a href="index.php" class="nav-link">
            <div class="nav-item">
                <img src="https://img.icons8.com/fluency-systems-regular/48/home.png"/>
                <div>Beranda</div>
            </div>
        </a>
        <a href="penjemputan.php" class="nav-link">
            <div class="nav-item">
                <img src="https://img.icons8.com/ios/50/clock--v1.png"/>
                <div>Penjemputan</div>
            </div>
        </a>
        <a href="pengantaran.php" class="nav-link">
            <div class="nav-item">
                <img src="https://img.icons8.com/ios/50/delivery.png"/>
                <div>Pengantaran</div>
            </div>
        </a>
        <a href="profil.php" class="nav-link">
            <div class="nav-item">
                <img src="https://img.icons8.com/ios/50/user.png"/>
                <div>Akun Saya</div>
            </div>
        </a>
    </div>

</body>
</html>
