<?php
require 'koneksi.php';
session_start();
if(empty($_SESSION['noHp'])) {
    header("location:login.php?message=belum_login");
}
$noHp = $_SESSION['noHp'];
$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$query = mysqli_query($connect, $sql);
$data = mysqli_fetch_array($query);
$name = $data['namaPelanggan'];
$alamat = $data['kabupaten'];


?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
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
    * {
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #fff;
      padding: 20px;
      padding-bottom: 80px; 
    }
    .notification-bell {
        position: relative;
        cursor: pointer;
        font-size: 1.5em;
        color: #333;
    }
    .notification-count {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.7em;
        font-weight: bold;
        line-height: 1;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .header .greeting {
      font-size: 20px;
      font-weight: bold;
    }

    .header .icons i {
      font-size: 18px;
      margin-left: 15px;
      color: #444;
      background-color: #f0f0f0;
      padding: 10px;
      border-radius: 50%;
    }

    .location-box {
      background-color: #3D4EB0;
      color: white;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 20px;
    }

    .location-box i {
      margin-right: 10px;
    }

    .offers-title {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .promo-banner {
      width: 100%;
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .promo-banner img {
      width: 100%;
      display: block;
    }

    .section-title {
      font-weight: bold;
      margin-bottom: 10px;
    }

    .services {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
      gap: 10px;
    }

    .service {
      flex: 1;
      background-color: #f3f3ff;
      border-radius: 12px;
      padding: 15px 10px;
      text-align: center;
    }

    .service i {
      font-size: 24px;
      color:rgb(61, 78, 176);
      margin-bottom: 8px;
    }

    .service p {
      font-weight: bold;
      font-size: 14px;
    }
    .card {
        background-color:rgba(61, 78, 176, 0.94);
        color: #FFFFFF
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
  </style>
</head>
<body onload = "getLocation();">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <div class="header">
    <div class="greeting">Hello, <?= $name ?>!</div>
  </div>

  <div class="location-box">
    <?php if ($alamat) : ?>
      <i class="fas fa-location-dot"></i> <strong><?php echo $alamat; ?></strong><br>
    <?php else: ?>
      <i class="fas fa-location-dot"></i> <strong><a href="lokasi.php?message=add" style="text-decoration: none;color: white">Atur lokasi</a></strong><br>
    <?php endif; ?>
  </div>

  <div class="offers-title">Latest Offers</div>
          <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="image/poster1.png" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="image/poster2.png" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="image/poster3.png" class="d-block w-100" alt="...">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div> 

<div class="div" style="padding-bottom: 70px;">
  <div class="section-title"><br>What do you want to get done today?</div>
  <div class="services">
    <div class="service">
      <a href="cuci_kering.php" style="text-decoration: none;color: black;">
        <img src="image/Cuci Kering.png" class="card-img-top" alt="...">
        <p>Cuci Kering</p>
      </a>
    </div>
    <div class="service">
      <a href="cuci_setrika.php" style="text-decoration: none;color: black;">
      <img src="image/Cuci Setrika.png" class="card-img-top" alt="...">
      <p>Cuci Setrika</p>
      </a>
    </div>
  </div>
  <footer>
    <div class="card mb-3" style="width: 100%;">
      <div class="row g-0">
        <div class="col-md-5">
          <div class="card-body">
          <h5 class="card-title">Lokasi Kami</h5>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3497.0505030558993!2d106.77330607439296!3d-6.115613759971877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1da1ed8d96e7%3A0x69b061ee15df6c8d!2sBos%20Laundry%20%26%20Dry%20Clean!5e1!3m2!1sid!2sid!4v1746168114750!5m2!1sid!2sid" width="100%" height="250" style="border:0; padding: 10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card-body">
            <h5 class="card-title">BOS Laundry & Dry Clean</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
          </div>
        </div>
      </div>
    </div>
    </footer>
</div>

  <div class="bottom-nav">
 <a href="dashboard.php" class="active">
      <span class="icon"><i class="fas fa-home"></i></span>
      Home
    </a>
    <a href="riwayat.php">
      <span class="icon"><i class="fas fa-history"></i></span>
    </a>
    <a href="profile.php">
      <span class="icon"><i class="fas fa-user"></i></span>
    </a>
</div>
</body>
</html>
