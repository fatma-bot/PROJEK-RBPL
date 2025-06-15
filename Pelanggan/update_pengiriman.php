<?php
session_start();
require 'koneksi.php';

if(empty($_SESSION['noHp'])) {
    header('location:login.php?message=belum_login');
    exit();
}

if(ISSET($_POST['submit'])) {
    $jenisPengiriman = $_POST['jenisPengiriman'];
    $idPesanan = $_POST['idPesanan'];
    $sql6 = "SELECT * FROM pesanan WHERE idPesanan = '$idPesanan'";
    $query6 = mysqli_query($connect, $sql6);
    $data = mysqli_fetch_array($query6);
    $totalBayar = $data['totalharga'];
    $statusPesanan = 'On Process';
    $statusPembayaran = "belum bayar";
    $sql = "UPDATE pesanan SET jenisPengiriman = '$jenisPengiriman', statusPesanan = '$statusPesanan' WHERE idPesanan = '$idPesanan'";
    $query = mysqli_query($connect, $sql);
    if($jenisPengiriman == "Ambil Sendiri") {
        $sql1 = "INSERT INTO pengambilan (idAmbil, idPesanan, statusAmbil) VALUES ('', '$idPesanan', 'diproses')";
        $query1 = mysqli_query($connect, $sql1);
    }
    else {
        $sql2 = "INSERT INTO pengantaran (idAntar, idPesanan, statusAntar) VALUES ('', '$idPesanan', 'diproses')";
        $query2 = mysqli_query($connect, $sql2);
    }
    $sql4 = "INSERT INTO pembayaran (idPembayaran, idPesanan, totalBayar, statusPembayaran) VALUES ('', '$idPesanan', '$totalBayar', '$statusPembayaran')";
    $query4 = mysqli_query($connect, $sql4);
    if($query) {
        header('location:pesananDibuat.php?id='.$idPesanan);
    }
}
?>