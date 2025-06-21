<?php
session_start();
if (!isset($_SESSION['idKurir'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f9f9f9;
        }
        .box {
            border: 1px solid #90ee90;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .box img {
            width: 80px;
            margin-bottom: 20px;
        }
        h3 {
            margin: 0;
            color: #4CAF50;
        }
        .button {
            margin-top: 30px;
            background: #FFD700;
            padding: 10px 30px;
            border-radius: 15px;
            text-decoration: none;
            color: black;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="box">
        <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" alt="Check">
        <h3>Pesanan Selesai Diantar</h3>
        <a href="pengantaran.php" class="button">KEMBALI</a>
    </div>
</body>
</html>
