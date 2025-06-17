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
    $sql7 = "SELECT * FROM pembayaran WHERE idPesanan = '$idPesanan'";
    $query7 = mysqli_query($connect, $sql7);
    $dataPembayaran = mysqli_fetch_array($query7);
    $statusPembayaran = $dataPembayaran['statusPembayaran'];
    if(!empty($statusPembayaran)) {
        if($statusPembayaran == 'belum bayar') {
            $statusPesanan = 'diproses';
        }
        else if ($statusPembayaran == 'dibayar'){
            $statusPesanan = 'selesai';
        }
    }
    else {
        $statusPesanan = 'diproses';
    }
    
    
    $statusPembayaran = "belum bayar";
    $sql = "UPDATE pesanan SET jenisPengiriman = '$jenisPengiriman', statusPesanan = '$statusPesanan' WHERE idPesanan = '$idPesanan'";
    $query = mysqli_query($connect, $sql);
    if($jenisPengiriman == "Ambil Sendiri") {
        $sql1 = "INSERT INTO pengambilan (idAmbil, idPesanan, statusAmbil) VALUES ('', '$idPesanan', 'diproses')";
        $query1 = mysqli_query($connect, $sql1);
    }
    else {
        $sqlKurir = "SELECT idKurir FROM kurir ORDER BY idKurir ASC"; // Urutkan untuk konsistensi
        $queryKurir = mysqli_query($connect, $sqlKurir);
        $kurirIds = [];
        while ($row = mysqli_fetch_assoc($queryKurir)) {
            $kurirIds[] = $row['idKurir'];
        }
        if (!empty($kurirIds)) {
            $lastAssignedKurirId = 0;
            $sqlLastKurir = "SELECT idKurir FROM penjemputan ORDER BY idJemput DESC LIMIT 1";
            $resultLastKurir = mysqli_query($connect, $sqlLastKurir);
            if (mysqli_num_rows($resultLastKurir) > 0) {
                $rowLastKurir = mysqli_fetch_assoc($resultLastKurir);
                $lastAssignedKurirId = $rowLastKurir['idKurir'];
            }
            
            $currentIndex = array_search($lastAssignedKurirId, $kurirIds);
            
            $nextIndex = ($currentIndex !== false && $currentIndex + 1 < count($kurirIds)) ? $currentIndex + 1 : 0;
            $idKurirYangDitugaskan = $kurirIds[$nextIndex];

            $date = date('Y-m-d');

            $sql3 = "INSERT INTO penjemputan (idJemput, idPesanan, idKurir, tglJemput, statusJemput) VALUES ('', '$idPesanan', '$idKurirYangDitugaskan', '$date', 'Diproses')";
            $query3 = mysqli_query($connect, $sql3);
            
            $sql2 = "INSERT INTO pengantaran (idAntar, idPesanan, idKurir, statusAntar) VALUES ('', '$idPesanan', '$idKurirYangDitugaskan', 'Diproses')";
            $query2 = mysqli_query($connect, $sql2);

            if (!$query3 || !$query2) {
                error_log("Error inserting into penjemputan or pengantaran: " . mysqli_error($connect));
            }

        } else {
            error_log("No couriers found in the database.");
        }
    }

    }
    $sql4 = "INSERT INTO pembayaran (idPembayaran, idPesanan, totalBayar, statusPembayaran) VALUES ('', '$idPesanan', '$totalBayar', '$statusPembayaran')";
    $query4 = mysqli_query($connect, $sql4);
    if($query) {
        header('location:pesananDibuat.php?id='.$idPesanan);
    }

?>