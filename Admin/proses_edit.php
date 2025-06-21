<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['idPesanan']) && isset($_POST['statusPesanan'])) {
    $id = $_POST['idPesanan'];
    $status = $_POST['statusPesanan'];

    $query = mysqli_query($connect, "UPDATE pesanan SET statusPesanan = '$status' WHERE idPesanan = '$id'");

    if ($query) {
        echo "<script>alert('Pesanan berhasil diperbarui'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Data tidak lengkap'); window.history.back();</script>";
}
?>
