<?php
$connect = mysqli_connect("localhost", "root", "", "laundry");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

?>