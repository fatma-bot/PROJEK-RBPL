<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (!isset($_GET['idPesanan'])) {
    echo "ID pesanan tidak ditemukan.";
    exit;
}

$idPesanan = $_GET['idPesanan'];

// Ambil data pesanan
$queryPesanan = mysqli_query($connect, "SELECT * FROM pesanan WHERE idPesanan = $idPesanan");
$dataPesanan = mysqli_fetch_assoc($queryPesanan);

if (!$dataPesanan) {
    echo "Data pesanan tidak ditemukan.";
    exit;
}

// Ambil data detail pakaian
$queryDetail = mysqli_query($connect, "SELECT * FROM detailpesanan WHERE idPesanan = $idPesanan");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pemesanan</title>
    <style>
        body {
            font-family: 'Courier', monospace;
            padding: 40px;
            background: #f4f4f4;
        }

        .btn-dashboard {
            display: inline-block;
            background-color:hsl(16, 93.10%, 66.10%);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .btn-dashboard:hover {
            background-color: #2c3e50;
        }
        .struk-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            margin: auto;
            /* box-shadow: 0 0 10px rgba(0,0,0,0.1); */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .text {
            font-size: 14px;
            text-align: center;
            margin-top: -10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info b {
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .button-group {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
        }

        .button-group button,
        .button-group a {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            text-decoration: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            min-width: 130px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .button-group a {
            background-color: #2ecc71;
        }

        .button-group button:hover {
            background-color: #2980b9;
        }

        .button-group a:hover {
            background-color: #27ae60;
        }

        @media print {
        .button-group,
        .btn-dashboard {
            display: none;
        }
    }

    </style>
</head>
<body>
<a href="index.php" class="btn-dashboard">Dashboard</a>
<div class="struk-container">
    <h2>Bos Laundry & Dry Clean</h2>
    <p class="text">Jl. Muara Karang Raya, RT.14/RW.18, Pluit, Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14450 - Telp. (021)123456</p>

    <div class="info">
        <p><b>Nama</b> : <?= htmlspecialchars($dataPesanan['namaPemesan']) ?></p>
        <p><b>Alamat</b> : <?= htmlspecialchars($dataPesanan['alamat']) ?></p>
        <p><b>Tanggal</b> : <?= $dataPesanan['tglPesan'] ?> <?= $dataPesanan['waktuPesan'] ?></p>
        <p><b>Layanan</b> : <?= $dataPesanan['jenisPesanan'] ?></p>
        <p><b>Pengiriman</b> : <?= $dataPesanan['jenisPengiriman'] ?></p>
        <!-- <p><b>Status:</b> <?= $dataPesanan['statusPesanan'] ?></p> -->
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Jenis Pakaian</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($queryDetail)) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['jenisPakaian'] ?></td>
            <td><?= $row['jmlPakaian'] ?></td>
            <td>Rp <?= number_format($row['hargaSatuan'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="total">
        <p><strong>Total Item:</strong> <?= $dataPesanan['totalItem'] ?></p>
        <p><strong>Total Harga:</strong> Rp <?= number_format($dataPesanan['totalharga'], 0, ',', '.') ?></p>
    </div>

</div>
    <div class="button-group">
        <button onclick="window.print()">Cetak Struk</button>
        <a href="cetak_pdf.php?idPesanan=<?= $idPesanan ?>">Unduh PDF</a>
    </div>

</body>
</html>
