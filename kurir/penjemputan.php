<?php
session_start();
if (!isset($_SESSION['idKurir'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Filter status berdasarkan tab aktif
$filter = $_GET['filter'] ?? 'semua';

// Filter status berdasarkan tab aktif
$filter = $_GET['filter'] ?? 'semua';

$query = "SELECT * FROM penjemputan";
if ($filter == 'belum') {
    $query .= " WHERE statusJemput = 'Diproses'";
} elseif ($filter == 'selesai') {
    $query .= " WHERE statusJemput = 'Selesai'";
}
$query .= " ORDER BY idJemput DESC";

$result = $connect->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Laundry</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #fff; }
        .tab-nav {
            display: flex;
            border-bottom: 1px solid #ccc;
            background: #fff;
        }
        .tab-nav a {
            flex: 1;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            color: #666;
            font-weight: bold;
        }
        .tab-nav a.active { color: red; }

        .pesanan-list { padding: 16px; }
        .pesanan-item {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .pesanan-title {
            font-size: 16px;
            font-weight: bold;
        }
        .pesanan-status {
            font-size: 14px;
        }

        .status-diproses {
            color: orangered;
        }

        .status-selesai {
            color: green;
        }

        .pesanan-date {
            font-size: 13px;
            color: #888;
        }

        .pesanan-link {
            text-decoration: none;
            color: black;
        }

        .main-image {
            text-align: center;
            margin-top: 6rem;
        }

        .main-image img {
            width: 200px;
            opacity: 0.5;
        }

        .main-image p {
            opacity: 0.3;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 25px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 5px;
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

        @media (max-width: 600px) {
            .pesanan-title { font-size: 14px; }
            .pesanan-status { font-size: 13px; }
            .pesanan-date { font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- Navigasi Tab Status -->
<div class="tab-nav">
    <a href="?filter=semua" class="<?= $filter == 'semua' ? 'active' : '' ?>">Semua</a>
    <a href="?filter=belum" class="<?= $filter == 'belum' ? 'active' : '' ?>">Belum dijemput</a>
    <a href="?filter=selesai" class="<?= $filter == 'selesai' ? 'active' : '' ?>">Selesai</a>
</div>

<!-- Daftar Pesanan -->
<div class="pesanan-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="pesanan-item">
                <a href="detail_penjemputan.php?id=<?= $row['idJemput'] ?>" class="pesanan-link">
                    <div class="pesanan-title">Penjemputan <?= htmlspecialchars($row['idJemput']) ?></div>
                    <div class="pesanan-status <?= $row['statusJemput'] == 'Diproses' ? 'status-diproses' : 'status-selesai' ?>">
                        <?= htmlspecialchars($row['statusJemput']) ?>
                    </div>
                    <div class="pesanan-date"><?= date('l, d F Y', strtotime($row['tglJemput'])) ?></div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="main-image">
            <img src="https://img.icons8.com/?size=100&id=E1YLsfPt7J9A&format=png&color=000000" alt="Ilustrasi file">
            <p>Belum ada pesanan</p>
        </div>
    <?php endif; ?>
</div>

<!-- Navigasi Bawah -->
<div class="bottom-nav">
    <a href="index.php" class="nav-link">
        <div class="nav-item">
            <img src="https://img.icons8.com/fluency-systems-regular/48/home.png"/>
            <div>Beranda</div>
        </div>
    </a>
    <a href="penjemputan.php" class="nav-link">
        <div class="nav-item">
            <img src="https://img.icons8.com/ios/50/clock--v1.png"/>
            <div>Penjemputan</div>
        </div>
    </a>
    <a href="pengantaran.php" class="nav-link">
        <div class="nav-item">
            <img src="https://img.icons8.com/ios/50/delivery.png"/>
            <div>Pengantaran</div>
        </div>
    </a>
    <a href="profil.php" class="nav-link">
        <div class="nav-item">
            <img src="https://img.icons8.com/ios/50/user.png"/>
            <div>Akun Saya</div>
        </div>
    </a>
</div>

</body>
</html>
