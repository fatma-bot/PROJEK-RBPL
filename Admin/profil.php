<?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil data user berdasarkan sesi login (misalnya ID = 1)
$idAdmin = 'idAdmin';
$sql = "SELECT namaAdmin, idAdmin, noHP FROM admin WHERE idAdmin = $idAdmin";
$result = $connect->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .container {
            padding: 20px;
            text-align: center;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #00BFFF;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }

        .profile-icon {
            margin-top: 60px;
            font-size: 100px;
            color: #ccc;
        }

        .info-section {
            margin-top: 30px;
            text-align: left;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .info-label {
            font-weight: bold;
            color: #2b2b2b;
            margin-bottom: 5px;
        }

        .info-value {
            background-color: #d9d9d9;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            background-color: #fff;
            padding: 0.5rem 0;
            border-top: 1px solid #ccc;
        }

        .nav-item {
            text-align: center;
            font-size: 0.85rem;
            color: #777;
        }

        .nav-item img {
            width: 24px;
            height: 24px;
        }

        .nav-link {
        text-decoration: none;
        color: black;
        }

        @media (max-width: 480px) {
            .status-card {
                width: 120px;
                padding: 0.8rem;
            }

            .status-card h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<button class="logout-btn" onclick="logout()">Logout</button>

<div class="container">
    <div class="profile-icon"><img src="https://img.icons8.com/?size=100&id=4V1nG4SioGjp&format=png&color=000000"></div>
    <div class="info-section">
        <div class="info-label">Nama</div>
        <div class="info-value">
            <?= htmlspecialchars($user['namaAdmin']) ?>
        </div>

        <div class="info-label">Nomor ID</div>
        <div class="info-value">
            <?= htmlspecialchars($user['idAdmin']) ?>
        </div>

        <div class="info-label">Nomor HP</div>
        <div class="info-value">
            <?= htmlspecialchars($user['noHP']) ?>
        </div>
    </div>
</div>

<div class="bottom-nav">
        <a href="index.php" class="nav-link">
            <div class="nav-item">
                <img src="https://img.icons8.com/fluency-systems-regular/48/home.png"/>
                <div>Kembali ke dashboard</div>
            </div>
        </a>
</div>

<script>
    function logout() {
        alert("Logout berhasil!");
        window.location.href = "logout.php"; 
    }
</script>

</body>
</html>
