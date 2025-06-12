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
    $statusPesanan = 'On Process';
    $sql = "UPDATE pesanan SET jenisPengiriman = '$jenisPengiriman', statusPesanan = '$statusPesanan' WHERE idPesanan = '$idPesanan'";
    $query = mysqli_query($connect, $sql);
    if($query) {
        header('location:pesananDibuat.php?id='.$idPesanan);
    }
}
?>