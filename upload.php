<?php
session_start();
require 'koneksi.php';

if(empty($_SESSION['noHp'])) {
    header("location:login.php?message=belum_login");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folder = "uploads/";
    $nama_file = basename($_FILES["foto"]["name"]);
    $path = $folder . uniqid() . "_" . $nama_file;
    $tipe_file = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    $boleh = ['jpg','jpeg','png','gif'];
    if (in_array($tipe_file, $boleh)) {
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $path)) {
            $noHp = $_SESSION['noHp'];
            $connect->query("UPDATE pelanggan SET foto='$path' WHERE noHp=$noHp");
            header("Location: profile.php");
            exit;
        } else {
            echo "Gagal upload.";
        }
    } else {
        echo "Format file tidak didukung.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: rgba(0,0,0,0.5); /* background gelap */
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .modal {
      background: #fff;
      border-radius: 10px;
      width: 400px;
      max-width: 90%;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      position: relative;
    }

    .modal h2 {
      margin-top: 0;
      font-size: 20px;
      text-align: center;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    input[type="file"],
    input[type="text"],
    select {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      background-color: #3D4EB0;
      color: #fff;
      border: none;
      padding: 10px 20px;
      width: 100%;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }
</style>
<body>
    <div class="modal">
        <form method="POST" enctype="multipart/form-data">
        Pilih Foto: <input type="file" name="foto" accept="image/*" required><br>
        <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>


