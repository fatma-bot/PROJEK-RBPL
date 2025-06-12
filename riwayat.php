<?php
session_start();
require 'koneksi.php';

if(empty($_SESSION['noHp'])) {
    header('location:login.php?message=belum_login');
    exit();
}
$orders = [
    ['type' => 'Cuci Setrika', 'location' => 'Muara Karang', 'datetime' => '10 April 2025, 10:22 AM', 'price' => 110000, 'status' => 'On Process'],
    ['type' => 'Cuci Kering', 'location' => 'Muara Karang', 'datetime' => '10 April 2025, 10:45 AM', 'price' => 100000, 'status' => 'On Process'],
    ['type' => 'Cuci Kering', 'location' => 'Muara Karang', 'datetime' => '10 April 2025, 07:00 AM', 'price' => 70000, 'status' => 'Completed'],
    ['type' => 'Cuci Kering', 'location' => 'Muara Karang', 'datetime' => '05 April 2025, 09:30 AM', 'price' => 150000, 'status' => 'Completed'],
    ['type' => 'Cuci Setrika', 'location' => 'Muara Karang', 'datetime' => '05 April 2025, 07:25 AM', 'price' => 80000, 'status' => 'Completed'],
];

$noHp = $_SESSION['noHp'];
$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$query = mysqli_query($connect, $sql);
$data = mysqli_fetch_array($query);
$name = $data['namaPelanggan'];
$kabupaten = $data['kabupaten'];

$sql2 = "SELECT * FROM pesanan WHERE namaPemesan = '$name' ORDER BY tglPesan DESC";
$query2 = mysqli_query($connect, $sql2);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #3D4EB0;
      --text-dark: #222;
      --text-light: #888;
      --background: #f9f9fc;
      --card-bg: #fff;
      --border: #eee;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--background);
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px 16px 80px;
    }

    h1 {
      text-align: center;
      font-size: 1.4rem;
      margin-bottom: 20px;
    }

    .search-bar {
      width: 100%;
      padding: 10px 15px;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      background-color: var(--primary);
      color: #fff;
      margin-bottom: 30px;
    }

    .section-title {
      font-weight: bold;
      color: var(--text-dark);
      margin: 20px 0 10px;
      font-size: 0.9rem;
    }

    .order-card {
      background: var(--card-bg);
      border-radius: 12px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      padding: 16px;
      margin-bottom: 12px;
    }

    .order-header {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .order-header img {
      width: 30px;
      height: 30px;
      margin-right: 10px;
    }

    .order-info {
      flex: 1;
    }

    .order-type {
      font-weight: bold;
      font-size: 1rem;
    }

    .order-location {
      font-size: 0.85rem;
      color: var(--text-light);
    }

    .order-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.85rem;
      margin-top: 5px;
    }

    .order-status {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: bold;
      text-align: center;
    }

    .status-OnProcess {
      background-color: #eaf1ff;
      color: #2f56c5;
    }

    .status-completed {
      background-color: #e1f7e8;
      color: #2e9c4f;
    }

    .order-price {
      font-weight: bold;
      color: var(--text-dark);
      font-size: 1rem;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: var(--primary);
      display: flex;
      justify-content: space-around;
      padding: 10px 0;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .bottom-nav a {
      color: white;
      text-align: center;
      font-size: 0.85rem;
      text-decoration: none;
    }

    .bottom-nav a.active {
      background: white;
      color: var(--primary);
      padding: 6px 12px;
      border-radius: 20px;
      font-weight: bold;
    }

    @media (min-width: 768px) {
      .container {
        padding: 30px 40px 100px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Riwayat Pesanan</h1>
  <input type="text" class="search-bar" placeholder="🔍 Search Orders...">

  <div class="section-title">Today</div>

  <?php
  if (mysqli_num_rows($query2) > 0) {
            while ($row = mysqli_fetch_assoc($query2)) {
    $date = $row['tglPesan'];
    $today = date('Y-m-d');
    if ($date == $today) {
      echo '<div class="order-card">
              <div class="order-header">
                <img src="image/' . htmlspecialchars($row['jenisPesanan']) . '.png" alt="icon">
                <div class="order-info">
                  <div class="order-type">' . htmlspecialchars($row['jenisPesanan']) . '</div>
                  <div class="order-location">' . htmlspecialchars($kabupaten) . '</div>
                </div>
              </div>
              <div class="order-meta">
                <span>' . htmlspecialchars($row['tglPesan']) . '</span>
                <span class="order-price">Rp ' . number_format($row['totalharga'], 0, ',', '.') . '</span>
              </div>
              <div class="order-meta">
                <div class="order-status ' . ($row['statusPesanan'] == 'Completed' ? 'status-completed' : 'status-OnProcess') . '">' . htmlspecialchars($row['statusPesanan']) . '</div>
              </div>
            </div>';
    }
    else { ?>
      <div class="section-title"><?php echo $date; ?></div>
  <?php
      echo '<div class="order-card">
              <div class="order-header">
                <img src="image/' . htmlspecialchars($row['jenisPesanan']) . '.png" alt="icon">
                <div class="order-info">
                  <div class="order-type">' . htmlspecialchars($row['jenisPesanan']) . '</div>
                  <div class="order-location">' . htmlspecialchars($kabupaten) . '</div>
                </div>
              </div>
              <div class="order-meta">
                <span>' . htmlspecialchars($row['tglPesan']) . '</span>
                <span class="order-price">Rp ' . number_format($row['totalharga'], 0, ',', '.') . '</span>
              </div>
              <div class="order-meta">
                <div class="order-status ' . ($row['statusPesanan'] == 'Completed' ? 'status-completed' : 'status-OnProcess') . '">' . htmlspecialchars($row['statusPesanan']) . '</div>
              </div>
            </div>';
    }
  }
    }
     else {
            echo "<p class='no-orders'>Anda belum memiliki riwayat pesanan.</p>";
        }
?>
</div>

<div class="bottom-nav">
 <a href="dashboard.php">
      <span class="icon"><i class="fas fa-home"></i></span>
    </a>
    <a href="riwayat.php" class="active">
      <span class="icon"><i class="fas fa-history"></i></span>
      Riwayat
    </a>
    <a href="profile.php">
      <span class="icon"><i class="fas fa-user"></i></span>
    </a>
</div>

</body>
</html>
