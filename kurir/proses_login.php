<?php
	session_start();

	include 'koneksi.php';

	$idKurir	= $_POST['idKurir'];
	$password 	= $_POST['password'];

	$sql		= "SELECT * FROM kurir WHERE idKurir='$idKurir' AND password='$password'";
	$query		= mysqli_query($connect, $sql);

	$cek 		= mysqli_num_rows($query);

	if($cek>0) {
		$_SESSION['idKurir'] 	= $idKurir;
		$_SESSION['status']		= "login";
		header("location: index.php");
	} else {
		header("location: login.php?message=failed");
	}
?>
