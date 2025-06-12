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
$noHp = $data['noHp'];
$foto = $data['foto'];
$foto_path = !empty($foto) ? 'uploads/' . urlencode($foto) : 'image/user.png';

if (!empty($foto) && file_exists("uploads/" . $foto)) {
    $fotoPath = "uploads/" . urlencode($foto);
} else {
    $fotoPath = "image/user.png"; // default image
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
      .profile-photo {
          width: 100px;
          height: 100px;
          border-radius: 50%;         /* Bikin bulat */
          object-fit: cover;          /* Supaya gambar nggak gepeng */
          display: block;
          margin: 0 auto 10px auto;   /* Tengahin + jarak bawah */
          border: 2px solid #ccc;     /* Opsional: tambahkan border */
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
<body>
    <nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 mx-auto">Profile</span>
    </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center" style="padding-top: 80px;">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <img src="<?= $fotoPath ?>" class="profile-photo" alt="Foto Profil">
                <strong><p class="card-text"><?= $name ?></p></strong>
                <p class="card-text" style="font-size: 12px;"><?= $noHp ?></p>
            </div>
        </div>
    </div> 

    <div class="d-flex justify-content-center mt-4">
        <div class="m-3" style="width: 300px;">
        <a href="edit_profil.php" class="list-group-item list-group-item-action mb-3 shadow-sm rounded py-3 px-4" style="display:block;">
            Edit Profile
        </a>

        <a href="alamat.php" class="list-group-item list-group-item-action mb-3 shadow-sm rounded py-3 px-4" style="display:block;">
            Alamat Saya
        </a>
        
        <a href="#" class="list-group-item list-group-item-action mb-3 shadow-sm rounded py-3 px-4" style="display:block;">
            Umpan Balik
        </a>
        
        <a href="#" class="list-group-item list-group-item-action mb-3 shadow-sm rounded py-3 px-4" style="display:block;">
            Bantuan
        </a>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4" style="padding-bottom: 80px;">
        <div class="m-3" style="width: 300px;">
        <a href="logout.php" class="list-group-item list-group-item-action mb-3 shadow-sm rounded py-3 px-4" style="display:block;">
            Log Out
        </a>
        </div>
    </div>

    <div class="bottom-nav">
 <a href="dashboard.php">
      <span class="icon"><i class="fas fa-home"></i></span>
    </a>
    <a href="riwayat.php">
      <span class="icon"><i class="fas fa-history"></i></span>
    </a>
    <a href="profile.php" class="active">
      <span class="icon"><i class="fas fa-user"></i></span>
      Profile
    </a>
</div>


</body>
</html>