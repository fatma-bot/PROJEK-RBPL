<?php
session_start();
if (!isset($_SESSION['idKurir'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil id penjemputan dari URL
$idJemput = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data penjemputan + pesanan
$query = "
    SELECT p.idJemput, p.idPesanan, p.statusJemput, p.idKurir, p.tglJemput, ps.alamat
    FROM penjemputan p
    JOIN pesanan ps ON p.idPesanan = ps.idPesanan
    WHERE p.idJemput = $idJemput
";
$result = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($result);

// Jika tombol diklik (toggle status)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statusSekarang = $_POST['statusJemput'];
    $statusBaru = ($statusSekarang === 'Diproses') ? 'Selesai' : 'Diproses';

    $update = "UPDATE penjemputan SET statusJemput = '$statusBaru' WHERE idJemput = $idJemput";
    mysqli_query($connect, $update);

    // Redirect ke halaman yang sama agar status terupdate
    if ($statusBaru === 'Selesai') {
    header("Location: status_penjemputan.php?id=$idJemput");
    } else {
        header("Location: detail_penjemputan.php?id=$idJemput");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Penjemputan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #333;
        }
        .field {
            margin-bottom: 16px;
        }
        .field label {
            font-weight: bold;
            color: #555;
        }
        .value-box {
            margin-top: 6px;
            background: #f1f1f1;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            color: #333;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #ffc107;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #e0a800;
        }
        .button-link {
        text-decoration: none;
        color: black;
        }
        @media (max-width: 600px) {
            .container {
                margin: 16px;
                padding: 16px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Penjemputan <?= htmlspecialchars($data['idJemput']) ?></h2>

    <?php if ($data): ?>
        <div class="field">
            <label>ID Pesanan</label>
            <div class="value-box"><?= $data['idPesanan'] ?></div>
        </div>
        <div class="field">
            <label>ID Kurir</label>
            <div class="value-box"><?= $data['idKurir'] ?></div>
        </div>
        <div class="field">
            <label>Tanggal Jemput</label>
            <div class="value-box"><?= date('l, d F Y', strtotime($data['tglJemput'])) ?></div>
        </div>
        <div class="field">
            <label>Alamat</label>
            <div class="value-box"><?= htmlspecialchars($data['alamat']) ?></div>
        </div>
        <div class="field">
            <label>Status</label>
            <div class="value-box"><?= $data['statusJemput'] ?></div>
        </div>

        <form method="POST">
            <input type="hidden" name="statusJemput" value="<?= $data['statusJemput'] ?>">
            <?php if ($data['statusJemput'] === 'Diproses'): ?>
                <button type="submit" class="button-link">SELESAI</button>
            <?php elseif ($data['statusJemput'] === 'Selesai'): ?>
                <button type="submit">KEMBALIKAN KE STATUS DIPROSES</button>
            <?php endif; ?>
        </form>

        <form action="penjemputan.php" method="get">
            <button type="submit">KEMBALI KE DAFTAR PENJEMPUTAN</button>
        </form>
    <?php else: ?>
        <p>Data tidak ditemukan.</p>
    <?php endif; ?>
</div>
</body>
</html>
