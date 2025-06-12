<?php
session_start();

require 'koneksi.php';
$noHp = $_POST['noHp'];
$password = $_POST['password'];

$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp' AND password = '$password'";
$query = mysqli_query($connect,$sql);

$cek = mysqli_num_rows($query);

if($cek >0)  {
    $_SESSION['noHp'] = $noHp;
    $_SESSION['status'] = "login";
    echo 
    "
    <script>
    alert('Login berhasil');
    document.location.href = 'dashboard.php';
    </script>
    "
    ;
} else {
    header("location:login.php?message=failed");
}

?>