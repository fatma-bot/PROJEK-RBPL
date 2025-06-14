<?php
session_start();
require 'koneksi.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['noHp'])) {
    header("location:login.php?message=belum_login");
    exit();
}

$noHp = $_SESSION['noHp'];
$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$query = mysqli_query($connect, $sql);
$data = mysqli_fetch_array($query);
$kabupaten = $data['kabupaten'];

$idPesanan = $_GET['id'] ?? null;

if (!$idPesanan) {
    die("ID Pesanan tidak ditemukan.");
}

$pesanan = null;
$stmt_master = mysqli_prepare($connect, "SELECT namaPemesan, alamat, tglPesan, waktuPesan, jenisPesanan, jenisPengiriman, totalItem, totalHarga, statusPesanan FROM pesanan WHERE idPesanan = ?");
if (!$stmt_master) {
    die("Error preparing master query: " . mysqli_error($connect));
}
mysqli_stmt_bind_param($stmt_master, "i", $idPesanan); 
mysqli_stmt_execute($stmt_master);
$result_master = mysqli_stmt_get_result($stmt_master);
$pesanan = mysqli_fetch_array($result_master, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_master);

if (!$pesanan) {
    die("Pesanan dengan ID " . htmlspecialchars($idPesanan) . " tidak ditemukan.");
}

$pesananDetail = [];
$stmt_detail = mysqli_prepare($connect, "SELECT jenisPakaian, jmlPakaian, hargaSatuan, subtotal FROM detailPesanan WHERE idPesanan = ?");
if (!$stmt_detail) {
    die("Error preparing detail query: " . mysqli_error($connect));
}
mysqli_stmt_bind_param($stmt_detail, "i", $idPesanan);
mysqli_stmt_execute($stmt_detail);
$result_detail = mysqli_stmt_get_result($stmt_detail);
while ($row = mysqli_fetch_array($result_detail, MYSQLI_ASSOC)) {
    $pesananDetail[] = $row;
}
mysqli_stmt_close($stmt_detail);

$tanggal_tampil = date('d-m-Y', strtotime($pesanan['tglPesan']));
$lokasi_pickup = $kabupaten; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Variabel CSS untuk Warna Utama */
        :root {
            --primary-color: #3D4EB0;
            --primary-light: #e0e6fa; /* Untuk background pilihan aktif */
            --text-dark: #333;
            --text-medium: #555;
            --text-light: #888;
            --background-light: #f0f2f5;
            --background-card: #fff;
            --border-light: #eee;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-inner: rgba(0,0,0,0.02);
        }

        /* CSS Internal */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-light);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center; /* Mengatur ke atas */
            min-height: 100vh;
            color: var(--text-dark);
        }
        form {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        .container {
            background-color: var(--background-card);
            border-radius: 0;
            box-shadow: 0 4px 20px var(--shadow-light);
            width: 100%;
            height: 100vh;
            max-width: 100%; 
            margin: 20px;
            overflow: hidden; /* Penting untuk border-radius */
        }

        .header {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header .back-arrow {
            font-size: 24px;
            margin-right: 15px;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
        }

        .header h1 {
            font-size: 1.2em;
            font-weight: 500;
            margin: 0;
            flex-grow: 1;
            text-align: center;
            transform: translateX(-20px); /* Geser ke kiri sedikit agar di tengah */
        }

        .info-bar {
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            background-color: var(--background-light);
            border-bottom: 1px solid var(--border-light);
        }

        .info-item {
            display: flex;
            align-items: center;
            font-size: 0.9em;
            color: var(--text-medium);
        }

        .info-item i {
            margin-right: 5px;
            color: var(--primary-color);
            font-size: 1.1em;
        }
        
        .content-section {
            padding: 20px;
        }

        .section-title {
            font-size: 1em;
            font-weight: 500;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        /* Styling untuk pilihan pengiriman (radio button kustom) */
        .delivery-option-wrapper {
            margin-bottom: 15px;
        }
        .delivery-option-wrapper input[type="radio"] {
            /* Sembunyikan radio button asli */
            display: none;
        }

        .delivery-option-label {
            background-color: var(--background-card); /* Default background */
            border: 1px solid var(--border-light);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-light); /* Default text color */
        }

        .delivery-option-label i {
            font-size: 20px;
            color: var(--text-light); /* Default icon color */
            margin-right: 10px;
        }
        
        /* State aktif saat radio button terpilih */
        .delivery-option-wrapper input[type="radio"]:checked + .delivery-option-label {
            background-color: var(--primary-light); /* Warna latar belakang pilihan pengiriman aktif */
            border-color: var(--primary-color); /* Border pilihan aktif */
            color: var(--text-dark);
        }
        .delivery-option-wrapper input[type="radio"]:checked + .delivery-option-label i {
            color: var(--primary-color);
        }

        /* Styling untuk radio button kustom */
        .custom-radio {
            width: 18px;
            height: 18px;
            border: 2px solid var(--text-light); /* Warna border default */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s ease;
            flex-shrink: 0; /* Mencegah custom radio mengecil */
        }

        .delivery-option-wrapper input[type="radio"]:checked + .delivery-option-label .custom-radio {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .delivery-option-wrapper input[type="radio"]:checked + .delivery-option-label .custom-radio::after {
            content: '';
            width: 8px;
            height: 8px;
            background-color: #fff;
            border-radius: 50%;
        }


        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 20px;
            font-size: 1em;
            font-weight: 500;
            color: var(--text-dark);
        }

        .summary-total .total-amount {
            font-size: 1.2em;
            font-weight: 700;
            color: var(--primary-color);
        }

        .order-item-list {
            background-color: #fcfcfc;
            border-radius: 10px;
            border: 1px solid var(--border-light);
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: inset 0 1px 5px var(--shadow-inner);
        }

        .order-type {
            font-size: 1em;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-top: 1px dashed var(--border-light);
            font-size: 0.9em;
            color: var(--text-medium);
        }
        .item-row:first-of-type {
            border-top: none;
        }

        .item-description {
            flex-grow: 1;
            margin-right: 10px; /* Jarak antara deskripsi dan harga */
        }
        .item-description small {
            color: var(--text-light);
            font-size: 0.8em;
        }

        .item-price {
            font-weight: 500;
            color: var(--text-dark);
            text-align: right;
            white-space: nowrap; /* Mencegah angka harga pindah baris */
        }

        .btn-confirm {
            width: calc(100% - 40px); /* Lebar tombol menyesuaikan container */
            padding: 15px 20px;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 20px 20px 20px; /* Margin bawah dan samping */
            box-sizing: border-box; /* Pastikan padding dan border termasuk dalam lebar */
        }

        .btn-confirm:hover {
            background-color: #304192; /* Warna lebih gelap saat hover */
        }

        /* Responsif */
        @media (max-width: 480px) {
            .container {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .header {
                padding: 15px 15px;
            }
            .content-section, .btn-confirm {
                padding-left: 15px;
                padding-right: 15px;
            }
             .btn-confirm {
                width: calc(100% - 30px); /* Adjusted width for smaller screens */
                margin: 0 15px 15px 15px;
             }
        }
        @media (min-width: 768px) {
            .container {
            padding: 20px 40px;
            border-radius: 15px;
            max-width: 900px;
            margin: 20px auto;
            height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="javascript:history.back()" class="back-arrow">&leftarrow;</a>
            <h1>Konfirmasi Pesanan</h1>
        </div>

        <div class="info-bar">
            <div class="info-item">
                <i class="far fa-calendar-alt"></i>
                <span><?php echo htmlspecialchars($tanggal_tampil); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo htmlspecialchars($lokasi_pickup); ?></span>
            </div>
        </div>

        <div class="content-section">
            <div class="section-title">Pilihan Pengiriman</div>
            
            <form id="" action="update_pengiriman.php" method="POST"> <input type="hidden" name="idPesanan" value="<?php echo htmlspecialchars($idPesanan); ?>">

                <div class="delivery-option-wrapper">
                    <input type="radio" id="self_pick_up" name="jenisPengiriman" value="Ambil Sendiri" <?php echo ($pesanan['jenisPengiriman'] == 'Ambil Sendiri') ? 'checked' : ''; ?>>
                    <label for="self_pick_up" class="delivery-option-label">
                        <i class="fas fa-store"></i>
                        <span>Self Pick-Up</span>
                        <div class="custom-radio"></div>
                    </label>
                </div>

                <div class="delivery-option-wrapper">
                    <input type="radio" id="kurir" name="jenisPengiriman" value="Kurir" <?php echo ($pesanan['jenisPengiriman'] == 'Kurir') ? 'checked' : ''; ?>>
                    <label for="kurir" class="delivery-option-label">
                        <i class="fas fa-truck"></i>
                        <span>Kurir</span>
                        <div class="custom-radio"></div>
                    </label>
                </div>
                
                <div class="summary-total">
                    <span>Total Pesanan (<?php echo htmlspecialchars($pesanan['totalItem']); ?> item):</span>
                    <span class="total-amount">Rp <?php echo number_format($pesanan['totalHarga'], 0, ',', '.'); ?></span>
                </div>

                <div class="order-item-list">
                    <div class="order-type"><?php echo htmlspecialchars($pesanan['jenisPesanan']); ?></div>
                    <?php foreach ($pesananDetail as $item) : ?>
                        <div class="item-row">
                            <div class="item-description">
                                <?php echo htmlspecialchars($item['jenisPakaian']); ?> @ <?php echo htmlspecialchars($item['jmlPakaian']); ?> item:
                                <br>
                                <small><?php echo htmlspecialchars($item['jmlPakaian']); ?> x Rp <?php echo number_format($item['hargaSatuan'], 0, ',', '.'); ?></small>
                            </div>
                            <div class="item-price">
                                Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" name="submit" class="btn-confirm">Konfirmasi Pesanan</button>
            </form>
        </div>
    </div>
</body>
</html>