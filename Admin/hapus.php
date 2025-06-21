<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Cek apakah parameter ID tersedia
if (isset($_GET['id'])) {
    $idPesanan = intval($_GET['id']);

    // Query hapus
    $query = "DELETE FROM pesanan WHERE idPesanan = $idPesanan";
    $result = $connect->query($query);

    if ($result) {
        // Jika berhasil, arahkan kembali ke dashboard
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . $connect->error;
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
