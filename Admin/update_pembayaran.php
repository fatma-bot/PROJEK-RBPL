<?php
require 'koneksi.php';
$today = date("Y-m-d");
$idPesanan = $_GET['id'];
$query = "SELECT * FROM pesanan WHERE idPesanan = '$idPesanan'";
$result = mysqli_query($connect, $query);
$data = mysqli_fetch_array($result);

$sql = "SELECT * FROM pembayaran WHERE idPesanan = '$idPesanan'";
$query2 = mysqli_query($connect, $sql);
$data2 = mysqli_fetch_array($query2);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Bukti Pengambilan</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .container label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        .container input[type="text"],
        .container input[type="date"],
        .container select {
            width: 100%;
            padding: 8px 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
        }

        .upload-box {
            margin-top: 15px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            color: #666;
            background: #f9f9f9;
        }

        .upload-box:hover {
            border-color: #aaa;
            background: #f1f1f1;
        }

        .upload-box input {
            display: none;
        }

        .upload-box label {
            cursor: pointer;
            display: block;
        }

        .submit-btn {
            display: block;
            margin: 25px auto 0;
            background-color: #38bdf8;
            color: white;
            padding: 10px 25px;
            font-size: 16px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0ea5e9;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .submit-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <form action="submit_pembayaran.php?id=<?php echo $data['idPesanan']; ?>" method="POST" enctype="multipart/form-data">
        <label for="order_no">No. Pesanan:</label>
        <input type="text" id="order_no" name="order_no" value="<?php echo $data['idPesanan']; ?>" readonly>

        <label for="customer_name">Nama Pesanan:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo $data['namaPemesan']; ?>" readonly>

        <label for="address">Alamat:</label>
        <input type="text" id="address" name="address" value="<?php echo $data['alamat']; ?>" readonly>

        <label for="shipping_option">Pilihan Pengiriman:</label>
        <input type="text" id="totalharga" name="totalharga" value="<?php echo $data['totalharga']; ?>" readonly>

        <label for="pickup_date">Tanggal Pengambilan:</label>
        <input type="date" id="tglPembayaran" min ="<?php echo $today; ?>" name="tglPembayaran">

        <label>Update Bukti Pengambilan:</label>
        <div class="upload-box">
            <label for="proof_file">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="Upload Icon" width="40"><br>
                Upload File Here
            </label>
            <input type="file" id="proof_file" name="tandaPembayaran" accept="image/*" required>
        </div>

        <button type="submit" class="submit-btn">Simpan</button>
    </form>
</div>

</body>
</html>
