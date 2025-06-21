<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($connect, "SELECT * FROM pesanan WHERE idPesanan = '$id'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pesanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f8;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 12px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn {
            margin-top: 25px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #3498db;
            color: white;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Status Pesanan</h2>
        <form method="POST" action="proses_edit.php">
            <input type="hidden" name="idPesanan" value="<?= $data['idPesanan'] ?>">

            <label>Nama Pemesan</label>
            <input type="text" value="<?= $data['namaPemesan'] ?>" readonly>

            <label>Alamat</label>
            <input type="text" value="<?= $data['alamat'] ?>" readonly>

            <label>Tanggal Pesan</label>
            <input type="date" value="<?= $data['tglPesan'] ?>" readonly>

            <label>Waktu Pesan</label>
            <input type="time" value="<?= $data['waktuPesan'] ?>" readonly>

            <label>Jenis Pesanan</label>
            <input type="text" value="<?= $data['jenisPesanan'] ?>" readonly>

            <label>Jenis Pengiriman</label>
            <input type="text" value="<?= $data['jenisPengiriman'] ?>" readonly>

            <label>Total Item</label>
            <input type="number" value="<?= $data['totalItem'] ?>" readonly>

            <label>Total Harga</label>
            <input type="number" value="<?= isset($data['totalharga']) ? $data['totalharga'] : '0' ?>" readonly>

            <label>Status Pesanan</label>
            <select name="statusPesanan" required>
                <option value="Diproses" <?= ($data['statusPesanan'] == 'proses') ? 'selected' : '' ?>>Diproses</option>
                <option value="Selesai" <?= ($data['statusPesanan'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>

            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="back-link" href="index.php">← Kembali ke Daftar</a>
        </form>
    </div>
</body>
</html>
