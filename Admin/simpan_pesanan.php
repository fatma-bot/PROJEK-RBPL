<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$nama = $_POST['namaPemesan'];
$alamat = $_POST['alamat'];
$pengiriman = $_POST['jenisPengiriman'];
$layanan = $_POST['jenisPesanan'];
$jenisPakaian = $_POST['jenisPakaian'];
$jumlah = $_POST['jumlah'];
$tgl = date('Y-m-d');
$waktu = date('H:i:s');

// Harga
$hargaList = [
    'Cuci Kering' => [
        'Jaket' => 50000,
        'Kemeja' => 25000,
        'Terusan / dress' => 45000,
        'Celana / Rok' => 30000
    ],
    'Cuci Setrika' => [
        'Jaket' => 55000,
        'Kemeja' => 30000,
        'Terusan / dress' => 50000,
        'Celana / Rok' => 35000
    ]
];

// Hitung total
$totalItem = 0;
$totalHarga = 0;
$subtotalList = [];

foreach ($jenisPakaian as $i => $jenis) {
    $jml = (int)$jumlah[$i];
    $harga = $hargaList[$layanan][$jenis];
    $subtotal = $harga * $jml;

    $subtotalList[] = [
        'jenis' => $jenis,
        'jumlah' => $jml,
        'harga' => $harga,
        'subtotal' => $subtotal
    ];

    $totalItem += $jml;
    $totalHarga += $subtotal;
}

// Simpan ke tabel pesanan
$sqlPesanan = "INSERT INTO pesanan (namaPemesan, alamat, tglPesan, waktuPesan, jenisPesanan, jenisPengiriman, totalItem, totalharga, statusPesanan)
VALUES ('$nama', '$alamat', '$tgl', '$waktu', '$layanan', '$pengiriman', $totalItem, $totalHarga, 'Diproses')";
$queryPesanan = mysqli_query($connect, $sqlPesanan);

if (!$queryPesanan) {
    die("Gagal menyimpan pesanan: " . mysqli_error($connect));
}

$idPesanan = mysqli_insert_id($connect);

// Simpan detail pesanan
foreach ($subtotalList as $item) {
    $jenis = $item['jenis'];
    $jml = $item['jumlah'];
    $harga = $item['harga'];
    $sub = $item['subtotal'];

    $sqlDetail = "INSERT INTO detailpesanan (idPesanan, jenisPakaian, jmlPakaian, hargaSatuan, subtotal)
                  VALUES ($idPesanan, '$jenis', $jml, $harga, $sub)";
    $queryDetail = mysqli_query($connect, $sqlDetail);

    if (!$queryDetail) {
        die("Gagal menyimpan detail pesanan: " . mysqli_error($connect));
    }
}

// Logika pengiriman
if ($pengiriman == "Ambil sendiri") {
    $sqlAmbil = "INSERT INTO pengambilan (idAmbil, idPesanan, statusAmbil)
                 VALUES ('', '$idPesanan', 'Diproses')";
    $queryAmbil = mysqli_query($connect, $sqlAmbil);
    if (!$queryAmbil) {
        die("Gagal menyimpan data pengambilan: " . mysqli_error($connect));
    }
}
elseif ($pengiriman == "Kurir") {
    $kurirIds = [];
    $sqlKurir = "SELECT idKurir FROM kurir ORDER BY idKurir ASC";
    $resultKurir = mysqli_query($connect, $sqlKurir);
    while ($row = mysqli_fetch_assoc($resultKurir)) {
        $kurirIds[] = $row['idKurir'];
    }

    if (!empty($kurirIds)) {
        $lastAssignedKurirId = 0;
        $sqlLastKurir = "SELECT idKurir FROM penjemputan ORDER BY idJemput DESC LIMIT 1";
        $resultLast = mysqli_query($connect, $sqlLastKurir);
        if (mysqli_num_rows($resultLast) > 0) {
            $lastAssignedKurirId = mysqli_fetch_assoc($resultLast)['idKurir'];
        }

        $currentIndex = array_search($lastAssignedKurirId, $kurirIds);
        $nextIndex = ($currentIndex !== false && $currentIndex + 1 < count($kurirIds)) ? $currentIndex + 1 : 0;
        $idKurirYangDitugaskan = $kurirIds[$nextIndex];

        $today = date('Y-m-d');

        $sqlJemput = "INSERT INTO penjemputan (idJemput, idPesanan, idKurir, tglJemput, statusJemput)
                      VALUES ('', '$idPesanan', '$idKurirYangDitugaskan', '$today', 'Diproses')";
        $queryJemput = mysqli_query($connect, $sqlJemput);

        if (!$queryJemput) {
            die("Gagal menyimpan data penjemputan: " . mysqli_error($connect));
        }

        $sqlAntar = "INSERT INTO pengantaran (idAntar, idPesanan, idKurir, statusAntar)
                     VALUES ('', '$idPesanan', '$idKurirYangDitugaskan', 'Diproses')";
        $queryAntar = mysqli_query($connect, $sqlAntar);

        if (!$queryAntar) {
            die("Gagal menyimpan data pengantaran: " . mysqli_error($connect));
        }
    } else {
        die("Tidak ada kurir tersedia.");
    }
}

// Simpan pembayaran
$sqlBayar = "INSERT INTO pembayaran (idPembayaran, idPesanan, totalBayar, statusPembayaran)
             VALUES ('', '$idPesanan', '$totalHarga', 'Belum Bayar')";
$queryBayar = mysqli_query($connect, $sqlBayar);

if (!$queryBayar) {
    die("Gagal menyimpan data pembayaran: " . mysqli_error($connect));
}

// Redirect ke struk
header("Location: struk.php?idPesanan=$idPesanan");
exit;
?>
