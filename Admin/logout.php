<?php
	session_start();
	session_destroy();

    echo "<script>alert('Logout berhasil!'); window.location.href='login.php';</script>";
?>
