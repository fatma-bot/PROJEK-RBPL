<?php
require 'koneksi.php';
$idPesanan = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tglAmbil = $_POST['tglAmbil'];
    $statusAmbil = "selesai";
    $folder = "uploads/";
    $nama_file = basename($_FILES["tandaPengambilan"]["name"]);
    $path = $folder . uniqid() . "_" . $nama_file;
    $tipe_file = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    $boleh = ['jpg','jpeg','png','gif'];
    if (in_array($tipe_file, $boleh)) {
        if (move_uploaded_file($_FILES['tandaPengambilan']['tmp_name'], $path)) {
            $noHp = $_SESSION['noHp'];
            $connect->query("UPDATE pengambilan SET tandaPengambilan='$path', statusAmbil='$statusAmbil', tglAmbil='$tglAmbil' WHERE idPesanan = $idPesanan");
            header("Location: pengiriman.php");
            exit;
        } else {
            echo "Gagal upload.";
        }
    } else {
        echo "Format file tidak didukung.";
    }
}