<?php
session_start();
require 'koneksi.php';

if(empty($_SESSION['noHp'])) {
  header("location:login.php?message=belum_login");
}

date_default_timezone_set('Asia/Jakarta');
$tglPesan = date('Y-m-d');
$waktuPesan = date('H:i:s');
$jenisPesanan = "Cuci Setrika";

$hargaPakaian = [
    'Jaket' => 55000, 
    'Kemeja' => 30000,  
    'Dress' => 50000, 
    'Celana' => 35000
];

$noHp = $_SESSION['noHp'];
$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$query = mysqli_query($connect, $sql);
$data = mysqli_fetch_array($query);
$kabupaten = $data['kabupaten'];
$namaPemesan = $data['namaPelanggan'];
$alamat = $data['jalan'].', '.$data['kecamatan'].', '.$data['kabupaten'];

$dataPesanan = [];
$totalItem = 0;
$totalharga = 0;

if(isset($_POST['submit'])) {
  if(isset($_POST['items']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $jenisPakaian => $jmlPakaian) {
      $jmlPakaian = (int) $jmlPakaian;
      if($jmlPakaian > 0 && array_key_exists($jenisPakaian, $hargaPakaian)) {
        $harga_per_unit = $hargaPakaian[$jenisPakaian];
        $subtotal_item = $jmlPakaian * $harga_per_unit;
        $totalItem += $jmlPakaian;
        $totalharga += $subtotal_item;
        $dataPesanan[] = [
          'jenisPakaian' => $jenisPakaian,
          'jmlPakaian' => $jmlPakaian,
          'harga_satuan' => $harga_per_unit,
          'subtotal' => $subtotal_item
        ];
      }
    }
  }
  $query1 = "INSERT INTO pesanan (idPesanan, namaPemesan, alamat, tglPesan, waktuPesan, jenisPesanan, totalItem, totalharga) VALUES ('', '$namaPemesan', '$alamat', '$tglPesan', '$waktuPesan', '$jenisPesanan', '$totalItem', '$totalharga')";
  mysqli_query($connect, $query1);

  $idPesanan = mysqli_insert_id($connect);
  
  foreach ($dataPesanan as $pesanan) {
    $jenisPakaian = $pesanan['jenisPakaian'];
    $jmlPakaian = $pesanan['jmlPakaian'];
    $harga_satuan = $pesanan['harga_satuan'];
    $subtotal = $pesanan['subtotal'];

    $query2 = "INSERT INTO detailPesanan (idPesanan, jenisPakaian, jmlPakaian, hargaSatuan, subtotal) VALUES ('$idPesanan', '$jenisPakaian', '$jmlPakaian', '$harga_satuan', '$subtotal')";
    mysqli_query($connect, $query2);

    echo 
      "
      <script>
      alert('Pesanan berhasil ditambahkan');
      document.location.href = 'konfirmasi.php?id=$idPesanan';
      </script>
      "
    ;
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 20px;
      background: #fff;
    }
    button {
      width: 100%;
      background-color: #3D4EB0;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #3D4EB0;
    }
    
    </style>
  </head>

  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
        </a>
        <div class="mx-auto text-center">
      <div class="navbar-brand mb-0 h1"><strong>Cuci Setrika </strong></div>
      <div style="font-size: 0.9rem;">
        📅 <?php echo date("d F Y"); ?> ||
        📍 <?php echo $kabupaten; ?>
      </div>
      </div>
      </div>
    </div>
    </nav>    
        
<div class="div" style="padding-bottom: 70px;padding-top: 70px;">
  <form action="" method="POST">
    <div class="accordion" id="accordionExample">
    <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Jaket <br> <?php echo "Rp".$hargaPakaian['Jaket']." @ 1 item"; ?> 
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>Jumlah item : </strong> <input type="number" id="jaket_qty" name="items[Jaket]" value="0" min="0">
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Kemeja <br> <?php echo "Rp".$hargaPakaian['Kemeja']." @ 1 item"; ?>
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>Jumlah item : </strong> <input type="number" id="kemeja_qty" name="items[Kemeja]" value="0" min="0">
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Terusan / dress <br> <?php echo "Rp".$hargaPakaian['Dress']." @ 1 item"; ?>
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>Jumlah item : </strong> <input type="number" id="dress_qty" name="items[Dress]" value="0" min="0">
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
        Celana / Rok <br> <?php echo "Rp".$hargaPakaian['Celana']." @ 1 item"; ?>
      </button>
    </h2>
    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>Jumlah item : </strong> <input type="number" id="celana_qty" name="items[Celana]" value="0" min="0">
      </div>
    </div>
  </div>
</div> <br> <br>
<button type="submit" name="submit">Tambah Pesanan</button>
</form>
  <div class="d-flex">
  <div class="card ms-auto">
    <div class="card-body">
      Total: <?php echo $totalharga; ?>
    </div>
  </div>
</div>
</div>
</body>
</html>