<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

require_once 'dompdf/autoload.inc.php'; // pastikan path ini sesuai
include 'koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$idPesanan = $_GET['idPesanan'] ?? 0;

// Ambil data pesanan
$queryPesanan = mysqli_query($connect, "SELECT * FROM pesanan WHERE idPesanan = $idPesanan");
$dataPesanan = mysqli_fetch_assoc($queryPesanan);
if (!$dataPesanan) {
    die("Data pesanan tidak ditemukan.");
}

$queryDetail = mysqli_query($connect, "SELECT * FROM detailpesanan WHERE idPesanan = $idPesanan");

$html = '
    <h2 style="text-align:center;">Bos Laundry & Dry Clean</h2>
    <p class="text" style="font-size: 14px; text-align: center; margin-top: -10px;">Jl. Muara Karang Raya, RT.14/RW.18, Pluit, Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14450 - Telp. (021)123456</p>
    <p><strong>Nama:</strong> ' . htmlspecialchars($dataPesanan['namaPemesan']) . '</p>
    <p><strong>Alamat:</strong> ' . htmlspecialchars($dataPesanan['alamat']) . '</p>
    <p><strong>Tanggal:</strong> ' . $dataPesanan['tglPesan'] . ' ' . $dataPesanan['waktuPesan'] . '</p>
    <p><strong>Layanan:</strong> ' . $dataPesanan['jenisPesanan'] . '</p>
    <p><strong>Pengiriman:</strong> ' . $dataPesanan['jenisPengiriman'] . '</p>
    
    <table border="1" cellspacing="0" cellpadding="6" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pakaian</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>';
$no = 1;
while ($row = mysqli_fetch_assoc($queryDetail)) {
    $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $row['jenisPakaian'] . '</td>
            <td>' . $row['jmlPakaian'] . '</td>
            <td>Rp ' . number_format($row['hargaSatuan'], 0, ',', '.') . '</td>
            <td>Rp ' . number_format($row['subtotal'], 0, ',', '.') . '</td>
        </tr>';
}
$html .= '
        </tbody>
    </table>
    <p style="text-align:right;"><strong>Total Item:</strong> ' . $dataPesanan['totalItem'] . '</p>
    <p style="text-align:right;"><strong>Total Harga:</strong> Rp ' . number_format($dataPesanan['totalharga'], 0, ',', '.') . '</p>
';

// Konfigurasi dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'potrait');
$dompdf->render();
$dompdf->stream('struk_pesanan_' . $idPesanan . '.pdf', ['Attachment' => true]);
exit;
