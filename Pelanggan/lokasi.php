<?php
session_start();
require 'koneksi.php'; // Pastikan file koneksi.php ada dan berfungsi

// Redirect jika belum login
if(empty($_SESSION['noHp'])) {
    header("location: login.php?message=belum_login");
    exit(); // Penting: hentikan eksekusi setelah redirect
}

$noHp = $_SESSION['noHp'];
$message_status = ''; // Untuk menampilkan pesan sukses/gagal

// Ambil data pelanggan saat ini untuk mengisi form jika ada
$current_kabupaten = '';
$current_kecamatan = '';
$current_jalan = '';
$current_latitude = ''; // Asumsi ada kolom latitude di tabel pelanggan
$current_longitude = ''; // Asumsi ada kolom longitude di tabel pelanggan

$sql_get_address = "SELECT namaPelanggan, kabupaten, kecamatan, jalan, latitude, longitude FROM pelanggan WHERE noHp = '$noHp'";
$query_get_address = mysqli_query($connect, $sql_get_address);
$address_data = mysqli_fetch_assoc($query_get_address);
$current_latitude = $address_data['latitude'];
$current_longitude = $address_data['longitude'];

// Menentukan judul form dan teks tombol berdasarkan keberadaan data
if ($address_data && (!empty($address_data['kabupaten']) || !empty($address_data['kecamatan']) || !empty($address_data['jalan']))) {
    // Data alamat ditemukan atau sudah pernah diisi
    $current_kabupaten = htmlspecialchars($address_data['kabupaten']);
    $current_kecamatan = htmlspecialchars($address_data['kecamatan']);
    $current_jalan = htmlspecialchars($address_data['jalan']);
    $current_latitude = htmlspecialchars($address_data['latitude']);
    $current_longitude = htmlspecialchars($address_data['longitude']);
    $form_title = 'Edit Alamat Saya';
    $submit_button_text = 'Perbarui Alamat';
} else {
    // Data alamat belum ada atau masih kosong
    $form_title = 'Tambah Alamat Baru';
    $submit_button_text = 'Simpan Alamat';
    // Anda bisa mengisi nilai default latitude/longitude di sini jika tidak ada di DB
    // $current_latitude = '-7.7956'; // Contoh koordinat Yogyakarta
    // $current_longitude = '110.3695';
}

// --- Proses Form Submission (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi dan sanitasi input
    $new_kabupaten = mysqli_real_escape_string($connect, $_POST['kabupaten']);
    $new_kecamatan = mysqli_real_escape_string($connect, $_POST['kecamatan']);
    $new_jalan = mysqli_real_escape_string($connect, $_POST['jalan']);

    // Query UPDATE selalu digunakan karena kita mengupdate kolom di baris pelanggan yang sudah ada
    $sql_action = "UPDATE pelanggan SET
                   kabupaten='$new_kabupaten',
                   kecamatan='$new_kecamatan',
                   jalan='$new_jalan'
                   WHERE noHp='$noHp'";

    // Jika Anda ingin mengupdate latitude dan longitude dari form (misal dari input hidden)
    // if (isset($_POST['latitude_form']) && isset($_POST['longitude_form'])) {
    //     $new_latitude_from_form = mysqli_real_escape_string($connect, $_POST['latitude_form']);
    //     $new_longitude_from_form = mysqli_real_escape_string($connect, $_POST['longitude_form']);
    //     $sql_action = "UPDATE pelanggan SET
    //                    kabupaten='$new_kabupaten',
    //                    kecamatan='$new_kecamatan',
    //                    jalan='$new_jalan',
    //                    latitude='$new_latitude_from_form',
    //                    longitude='$new_longitude_from_form'
    //                    WHERE noHp='$noHp'";
    // }


    if (mysqli_query($connect, $sql_action)) {
        $message_status = "<div class='success-message'>Alamat berhasil disimpan/diperbarui!</div>";
        // Update current values agar form menampilkan data terbaru
        $current_kabupaten = $new_kabupaten;
        $current_kecamatan = $new_kecamatan;
        $current_jalan = $new_jalan;
        // Opsional: Jika Anda ingin redirect setelah sukses, uncomment baris di bawah
        header("Location: alamat.php?status=success");
        exit();
    } else {
        $message_status = "<div class='error-message'>Gagal menyimpan/memperbarui alamat: " . mysqli_error($connect) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $form_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Variabel CSS untuk warna */
        :root {
            --primary-color: #3D4EB0;
            --text-color: #333;
            --border-color: #ced4da;
            --success-bg: #d4edda;
            --success-text: #155724;
            --error-bg: #f8d7da;
            --error-text: #721c24;
            --navbar-height: 60px;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f2f2f2; /* Mirip light-gray Anda */
            color: var(--text-color);
            padding-top: 100px;
            padding-bottom: 20px;
        }

        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: var(--navbar-height); /* Terapkan tinggi navbar */
            display: flex; /* Untuk centering konten navbar */
            align-items: center; /* Untuk centering konten navbar */
        }
        .navbar .container-fluid {
            padding: 0 15px; /* Sesuaikan padding */
        }
        .navbar-brand {
            color: black;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .navbar-brand svg {
            margin-right: 10px;
        }
        .navbar .mx-auto {
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center; /* Pastikan teks di tengah */
            flex-grow: 1; /* Agar mengambil ruang sisa */
        }

        .container-main { /* Ganti nama kelas agar tidak bentrok */
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 100vh;
        }

        .form-section {
            padding: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px; /* Jarak antar input */
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box; /* Agar padding dan border tidak menambah lebar */
            font-size: 1em;
        }

        strong {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1em;
            font-weight: 500;
            color: var(--text-color);
        }

        .map-iframe {
            width: 100%;
            height: 300px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .confirm-button { /* Menggunakan nama yang lebih unik dari 'button' */
            background-color: var(--primary-color);
            color: white;
            width: 100%;
            border-radius: 50px;
            padding: 12px 20px;
            font-size: 1.1em;
            font-weight: 500;
            border: none;
            cursor: pointer;
            display: block; /* Agar menempati lebar penuh */
            text-align: center;
            transition: background-color 0.2s ease;
        }
        .confirm-button:hover {
            background-color: #303e9f; /* Sedikit lebih gelap */
        }

        /* Styling untuk pesan sukses/error */
        .message {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: 500;
        }
        .success-message {
            background-color: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: var(--error-bg);
            color: var(--error-text);
            border: 1px solid #f5c6cb;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container-main {
                box-shadow: none;
                padding-top: 70px; /* Sedikit kurang padding atas */
            }
            .form-section {
                padding: 15px;
            }
            .navbar .navbar-brand svg {
                margin-right: 5px;
            }
            .navbar-brand h1 {
                font-size: 1.1em;
            }
            .confirm-button {
                padding: 10px 15px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </a>
            <span class="navbar-brand mb-0 h1 mx-auto"><?php echo $form_title; ?></span>
        </div>
    </nav>

    <div class="container-main">
        <div class="form-section">
            <?php echo $message_status; ?>

            <form action="" method="POST">
                <strong>Alamat</strong>
                <input class="form-control" type="text" name="kabupaten" placeholder="Kota/Kabupaten" value="<?php echo $current_kabupaten; ?>" required>
                <input class="form-control" type="text" name="kecamatan" placeholder="Kecamatan" value="<?php echo $current_kecamatan; ?>" required>
                <input class="form-control" type="text" name="jalan" placeholder="Nama Jalan, Gedung, No. Rumah, dll." value="<?php echo $current_jalan; ?>" required>

                <?php if (!empty($current_latitude) && !empty($current_longitude)): ?>
                    <iframe
                        class="map-iframe"
                        src="https://maps.google.com/maps?q=<?php echo $current_latitude; ?>,<?php echo $current_longitude; ?>&hl=id&z=14&output=embed"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                <?php else: ?>
                    <p style="text-align: center; color: #666; margin-bottom: 20px;">Lokasi peta akan muncul setelah latitude dan longitude tersedia di database Anda.</p>
                <?php endif; ?>

                <button class="confirm-button" type="submit"><?php echo $submit_button_text; ?></button>
            </form>
        </div>
    </div>
</body>
</html>