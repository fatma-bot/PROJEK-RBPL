<?php
session_start();
if (!isset($_SESSION['idKurir'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$idKurir = $_SESSION['idKurir'] ?? '';
$namaKurir = $idKurir; // fallback jika nama tidak ditemukan

if (!empty($idKurir)) {
    $sql = "SELECT namaKurir FROM kurir WHERE idKurir = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $idKurir);
    $stmt->execute();
    $stmt->bind_result($hasil_nama);
    if ($stmt->fetch()) {
        $namaKurir = $hasil_nama;
    }
    $stmt->close();
}

return $namaKurir;
?>
