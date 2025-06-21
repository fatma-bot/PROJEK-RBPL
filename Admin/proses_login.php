<?php
	session_start();

	include 'koneksi.php';

	$idAdmin	= $_POST['idAdmin'];
	$password 	= $_POST['password'];

	$sql		= "SELECT * FROM admin WHERE idAdmin ='$idAdmin' AND password='$password'";
	$query		= mysqli_query($connect, $sql);

	$cek 		= mysqli_num_rows($query);

	if($cek>0) {
		$_SESSION['idAdmin'] 	= $idAdmin;
		$_SESSION['status']		= "login";
		echo "<script>alert('Login berhasil!'); window.location.href='index.php';</script>";
	} else {
		echo "<script>alert('ID atau Password salah!'); window.history.back();</script>";
	}
?>

