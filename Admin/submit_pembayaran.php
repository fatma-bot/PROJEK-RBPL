<?php
require 'koneksi.php';
$idPesanan = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tglPembayaran = $_POST['tglPembayaran'];
    $statusPembayaran = "dibayar";
    $statusPesanan = "selesai";
    $sql = "UPDATE pesanan SET statusPesanan = '$statusPesanan' WHERE idPesanan = '$idPesanan'";
    $query = mysqli_query($connect, $sql);
    $folder = "uploads/";
    $nama_file = basename($_FILES["tandaPembayaran"]["name"]);
    $path = $folder . uniqid() . "_" . $nama_file;
    $tipe_file = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    $boleh = ['jpg','jpeg','png','gif'];
    if (in_array($tipe_file, $boleh)) {
        if (move_uploaded_file($_FILES['tandaPembayaran']['tmp_name'], $path)) {
            $noHp = $_SESSION['noHp'];
            $connect->query("UPDATE pembayaran SET tandaPembayaran='$path', statusPembayaran='$statusPembayaran', tglPembayaran='$tglPembayaran' WHERE idPesanan = $idPesanan");
            header("Location: pembayaran.php");
            exit;
        } else {
            echo "Gagal upload.";
        }
    } else {
        echo "Format file tidak didukung.";
    }
}