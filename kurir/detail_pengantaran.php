<?php
session_start();
if (!isset($_SESSION['idKurir'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil id pengantaran dari URL
$idAntar = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pengantaran + pesanan
$query = "
    SELECT p.idAntar, p.idPesanan, p.statusAntar, p.idKurir, p.tglAntar, ps.alamat, ps.totalharga, pb.statusPembayaran
    FROM pengantaran p
    JOIN pesanan ps ON p.idPesanan = ps.idPesanan
    LEFT JOIN pembayaran pb ON ps.idPesanan = pb.idPesanan
    WHERE p.idAntar = $idAntar
";

$result = mysqli_query($connect, $query);

// Validasi jika query gagal
if (!$result) {
    echo "<p>Terjadi kesalahan dalam query: " . mysqli_error($connect) . "</p>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Validasi jika data tidak ditemukan
if (!$data) {
    echo "<p>Data tidak ditemukan atau ID pengantaran tidak valid.</p>";
    exit;
}

// Jika tombol diklik (toggle status)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statusSekarang = $_POST['statusAntar'];
    $statusBaru = ($statusSekarang === 'Diproses') ? 'Selesai' : 'Diproses';
    $statusPembayaranBaru = $_POST['statusPembayaran'];
    
    $tglAntar = isset($_POST['tglAntar']) && $_POST['tglAntar'] !== '' ? $_POST['tglAntar'] : date('Y-m-d');

    // Update status dan tanggal antar
    $update = "UPDATE pengantaran 
               SET statusAntar = '$statusBaru', tglAntar = '$tglAntar' 
               WHERE idAntar = $idAntar";
    mysqli_query($connect, $update);

    $idPesanan = $data['idPesanan'];

    // Update pembayaran bila status berubah
    if ($data['statusPembayaran'] === 'Belum Bayar' && $statusPembayaranBaru === 'Dibayar') {
        $updatePembayaran = "UPDATE pembayaran 
            SET statusPembayaran = 'Dibayar', tglPembayaran = '$tglAntar' 
            WHERE idPesanan = $idPesanan";
        mysqli_query($connect, $updatePembayaran);
    }

    // Redirect untuk refleksikan perubahan
    if ($statusBaru === 'Selesai') {
        header("Location: status_pengantaran.php?id=$idAntar");
    } else {
        header("Location: detail_pengantaran.php?id=$idAntar");
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pengantaran</title>
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
        input[type="date"], select {
            margin-top: 6px;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: #fff;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }

        input[type="date"]:focus, select:focus {
            border-color: #ffc107;
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.3);
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
    <h2>Pengantaran <?= htmlspecialchars($data['idAntar']) ?></h2>

<form method="POST">

    <div class="field">
        <label>ID Pesanan</label>
        <div class="value-box"><?= $data['idPesanan'] ?></div>
    </div>
    <div class="field">
        <label>ID Kurir</label>
        <div class="value-box"><?= $data['idKurir'] ?></div>
    </div>
    <div class="field">
        <label>Tanggal Antar</label>
        <?php
        $tanggalTerisi = ($data['tglAntar'] !== "0000-00-00") ? $data['tglAntar'] : date('Y-m-d');
        ?>
        <input type="date" name="tglAntar" value="<?= $tanggalTerisi ?>">
    </div>
    <div class="field">
        <label>Alamat</label>
        <div class="value-box"><?= htmlspecialchars($data['alamat']) ?></div>
    </div>
    <div class="field">
        <label>Total Harga</label>
        <div class="value-box">Rp <?= number_format($data['totalharga'], 2, ',', '.') ?></div>
    </div>

    <input type="hidden" name="statusAntar" value="<?= $data['statusAntar'] ?>">

        <div class="field">
            <label>Status Pembayaran</label>
            <div class="value-box">
                <?php if ($data['statusPembayaran'] === 'Belum Bayar' && $data['statusAntar'] !== 'Selesai'):?>
                    <select name="statusPembayaran"> 
                        <option value="Belum Bayar" <?= ($data['statusPembayaran'] === 'Belum Bayar') ? 'selected' : '' ?>>Belum Bayar</option>
                        <option value="Dibayar" <?= ($data['statusPembayaran'] === 'Dibayar') ? 'selected' : '' ?>>Dibayar</option>
                    </select>
                <?php else: ?>
                    <?= htmlspecialchars($data['statusPembayaran']) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="field">
            <label>Status Pengantaran</label>
            <div class="value-box"><?= $data['statusAntar'] ?></div>
        </div>

        <?php if ($data['statusAntar'] === 'Diproses'): ?>
            <button type="submit" class="button-link">SELESAIKAN PENGANTARAN</button>
        <?php elseif ($data['statusAntar'] === 'Selesai'): ?>
            <button type="submit">UBAH KEMBALI KE DIPROSES</button>
        <?php endif; ?>
    </form>

    <form action="pengantaran.php" method="get">
        <button type="submit">KEMBALI KE DAFTAR PENGANTARAN</button>
    </form>
</div>
</body>
</html>