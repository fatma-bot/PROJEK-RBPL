<?php
session_start();
require 'koneksi.php'; 

if(empty($_SESSION['noHp'])){
    header("location: login.php?message=belum_login");
    exit();
}

$noHp = $_SESSION['noHp'];

$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$query = mysqli_query($connect, $sql);
$data = mysqli_fetch_array($query);
$nama = $data['namaPelanggan'];
$kabupaten = $data['kabupaten'];
$kecamatan = $data['kecamatan'];
$jalan = $data['jalan'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alamat Saya</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3D4EB0;
            --text-color: #333;
            --light-gray: #f2f2f2;
            --border-color: #ddd;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: var(--light-gray);
            color: var(--text-color);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-height: 100vh;
        }

        header {
            background-color: var(--primary-color);
            color: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header .back-arrow {
            font-size: 24px;
            margin-right: 15px;
            cursor: pointer;
        }

        header h1 {
            margin: 0;
            font-size: 1.2em;
            font-weight: 500;
        }

        .address-section {
            padding: 20px;
        }

        .address-card {
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .address-card .name {
            font-weight: 700;
            font-size: 1.1em;
            color: var(--text-color);
        }

        .address-card .phone {
            font-size: 0.95em;
            color: #555;
        }

        .address-card .full-address {
            font-size: 1em;
            color: var(--text-color);
            line-height: 1.5;
        }

        .address-card .tag {
            background-color: var(--primary-color);
            color: #fff;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            display: inline-block;
            margin-top: 10px;
            font-weight: 500;
        }

        .action-button { /* Menggunakan nama umum untuk tombol aksi */
            display: block;
            background-color: var(--primary-color);
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.2s ease;
        }

        .action-button:hover {
            background-color: #303e9f;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                box-shadow: none;
            }

            header {
                padding: 12px 15px;
            }

            .address-section {
                padding: 15px;
            }

            .address-card {
                padding: 15px;
            }

            .action-button {
                padding: 10px 15px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <span class="back-arrow" onclick="window.location.href='profile.php'">&#8592;</span>
            <h1>Alamat Saya</h1>
        </header>
<?php
    if(isset($_GET['status'])) {
                if($_GET['status'] == "success") { ?>
                    <div class="custom-alert">
                        <?php echo "Alamat berhasil diperbarui"; ?>
                    </div>
                    <script>
                        setTimeout(function () {
                        const alertElement = document.querySelector('.custom-alert');
                        if (alertElement) {
                            alertElement.style.display = 'none';
                        }
                        }, 2000);
                    </script>
                <?php
                }
            }
    ?>
        <div class="address-section">
            <?php if ($kabupaten): ?>
                <div class="address-card">
                    <div class="name"><?php echo $nama; ?></div>
                    <div class="phone"><?php echo $noHp; ?></div>
                    <div class="full-address">
                        <?php echo "Jalan ", $jalan, ", Kec. ", $kecamatan, ", Kab. ", $kabupaten; ?><br>
                    </div>
                </div>
                <a href="lokasi.php?message=edit" class="action-button">
                    Edit Alamat
                </a>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #666;">Anda belum memiliki alamat tersimpan.</p>
                <a href="lokasi.php?message=add" class="action-button">
                    Tambah Alamat Baru
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>