<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 90%;
      max-width: 400px;
    }
    .custom-alert {
        padding: 12px;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 14px;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    h2 {
      text-align: center;
      margin-bottom: 5px;
    }

    p.subtitle {
      text-align: center;
      color: #888;
      margin-top: 0;
      font-size: 14px;
    }

    .form-group {
      margin: 15px 0;
    }

    .form-input {
      width: 100%;
      padding: 12px 40px 12px 45px;
      border: 1px solid #ccc;
      border-radius: 6px;
      position: relative;
      font-size: 14px;
    }

    .form-group i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      font-size: 16px;
      color: #888;
    }

    .form-group.flag-input {
      display: flex;
      align-items: center;
    }

    .form-group.flag-input span {
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px 0 0 6px;
      background-color: #f9f9f9;
      font-size: 14px;
    }

    .form-group.flag-input input {
      border-radius: 0 6px 6px 0;
      border-left: none;
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

    .login-link {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .login-link a {
      color: #3D4EB0;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .icon-wrapper {
      position: relative;
    }

    .icon-wrapper i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #888;
    }
  </style>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body onload = "getLocation();">
    <div class="container">
<?php
require 'koneksi.php';
if(isset($_POST["submit"])) {
    $noHp = $_POST["noHp"];
    $namaPelanggan = $_POST["namaPelanggan"];
    $password = $_POST["password"];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql_get = mysqli_query($connect, "SELECT * FROM pelanggan WHERE noHp = '$noHp'");
    $num_row = mysqli_num_rows($sql_get);
    if ($num_row == 0) {
            $query = "INSERT INTO pelanggan (idPelanggan, noHp, namaPelanggan, password, latitude, longitude) VALUES ('', '$noHp', '$namaPelanggan', '$password', '$latitude', '$longitude')";
            mysqli_query($connect, $query);
            echo 
            "
            <script>
            alert('Registrasi berhasil');
            document.location.href = 'login.php';
            </script>
            "
            ;
    }
    else {
        ?>
        <div class="custom-alert" role="alert">
            <?php echo "maaf nomor hp ini sudah terdaftar"; ?>
        </div>
        <script>
            setTimeout(function () {
            const alertElement = document.querySelector('.custom-alert');
            if (alertElement) {
                alertElement.style.display = 'none';
            }
            }, 2000);
        </script>
        <?php
    }
}
?>

  
    <h2>REGISTRASI</h2>
    <p class="subtitle">Silahkan masukkan data diri Anda</p>

    <!-- nanti disesuaikan dengan database -->
    <form action="" method="POST" class="myForm">
      <div class="form-group icon-wrapper">
        <i class="fas fa-user"></i>
        <input type="text" name="noHp" class="form-input" placeholder="Nomor telepon" required>
      </div>

      <div class="form-group icon-wrapper">
        <i class="fas fa-user"></i>
        <input type="text" name="namaPelanggan" class="form-input" placeholder="Nama" required>
      </div>

      <div class="form-group icon-wrapper">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" class="form-input" placeholder="Password" required>
      </div>
      <input type="hidden" name="latitude" value=""> 
      <input type="hidden" name="longitude" value="">
      <button type="submit" name="submit">DAFTAR</button>
    </form>

    <div class="login-link">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>
  </div>

  <script type="text/javascript">
    function getLocation() {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition,showError);
        }
    }
    function showPosition(position) {
        document.querySelector('.myForm input[name = "latitude"]').value = position.coords.latitude;
        document.querySelector('.myForm input[name = "longitude"]').value = position.coords.longitude;
    }
    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED: 
                alert("Izin Lokasi Harus Diaktifkan");
                location.reload();
                break;
        }
    }
   </script>

</body>
</html>
