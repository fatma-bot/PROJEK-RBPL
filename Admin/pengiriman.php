<?php
//session_start();
require 'koneksi.php';


// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
//     $no_pesanan_update = $_POST['no_pesanan_update'];
//     $new_status = $_POST['new_status'];

//     // Gunakan Prepared Statements untuk mencegah SQL Injection
//     $stmt = $conn->prepare("UPDATE pengiriman SET status = ? WHERE no_pesanan = ?");
//     $stmt->bind_param("ss", $new_status, $no_pesanan_update);

//     if ($stmt->execute()) {
//         $message = "<div class='alert success'>Status berhasil diperbarui!</div>";
//     } else {
//         $message = "<div class='alert error'>Error: " . $stmt->error . "</div>";
//     }
//     $stmt->close();
// }

// Ambil data pengiriman dari database
$sql = "SELECT idPesanan, namaPemesan, alamat, jenisPengiriman FROM pesanan";
$result = $connect->query($sql);

$data_pengiriman = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_pengiriman[] = $row;
        $idPesanan = $row['idPesanan'];
        $jenisPengiriman = $row['jenisPengiriman'];
        if($jenisPengiriman == "Ambil Sendiri") {
             $sql2 = "SELECT * FROM pengambilan WHERE idPesanan = '$idPesanan'";
            $query = mysqli_query($connect, $sql2);
            $dataPengambilan = mysqli_fetch_array($query);
            $status = $dataPengambilan['statusAmbil'];
        }
        else {
            $sql1 = "SELECT * FROM pengantaran WHERE idPesanan = '$idPesanan'";
            $query = mysqli_query($connect, $sql1);
            $dataPengantaran = mysqli_fetch_array($query);
            $status = $dataPengantaran['statusAntar'];
        }
    }
}

$connect->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Clean - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* CSS Internal */
        :root {
            --primary-blue: #007bff;
            --light-blue: #e0f2f7;
            --dark-gray: #333;
            --medium-gray: #666;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--light-gray);
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--primary-blue);
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            box-shadow: 2px 0 5px var(--shadow-color);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .sidebar-header .icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .sidebar-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-nav li {
            margin-bottom: 10px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease, border-left 0.3s ease;
            border-left: 5px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 5px solid white;
        }

        .sidebar-nav a .icon {
            margin-right: 15px;
            font-size: 20px;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: var(--light-gray);
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .navbar h2 {
            color: var(--dark-gray);
            margin: 0;
        }

        .navbar .icons {
            font-size: 24px;
            color: var(--medium-gray);
        }

        .page-title {
            color: var(--primary-blue);
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Table */
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px var(--shadow-color);
            overflow-x: auto; /* Untuk responsif pada tabel */
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            text-align: left;
            font-size: 15px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: var(--light-blue);
            font-weight: 600;
            color: var(--dark-gray);
            white-space: nowrap; /* Mencegah judul kolom terlalu pendek */
        }

        .data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .data-table tr:hover {
            background-color: #e9e9e9;
        }
        
        .status-action-group {
            display: flex; /* Menggunakan flexbox untuk mensejajarkan item */
            align-items: center; /* Pusatkan secara vertikal */
            gap: 8px; /* Memberi sedikit jarak antar elemen */
        }

        /* Jika Anda ingin teks statusnya rata kanan seperti di gambar awal, atau gaya lain */
        .current-status-display {
            white-space: nowrap; /* Mencegah teks melompat baris */
            color: var(--dark-gray); /* Contoh warna teks status */
            font-weight: 500;
        }

        /* Pertahankan gaya .status-button yang sudah ada */
        .status-button {
            background-color: #6c757d;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        /* .status-button {
            background-color: #6c757d;  Default gray for update 
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .status-button:hover {
            background-color: #5a6268;
        }

        .status-button .icon {
            font-size: 16px;
        } */

        /* Modal (Pop-up untuk Update Status) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow-color);
            width: 90%;
            max-width: 400px;
            position: relative;
        }

        .close-button {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content h3 {
            color: var(--dark-gray);
            margin-bottom: 20px;
            text-align: center;
        }

        .modal-content label {
            display: block;
            margin-bottom: 8px;
            color: var(--medium-gray);
            font-weight: 500;
        }

        .modal-content select,
        .modal-content input[type="hidden"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 16px;
        }

        .modal-content button[type="submit"] {
            background-color: var(--primary-blue);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .modal-content button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Alerts */
        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsif */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 15px 20px;
                border-radius: 0;
                border-bottom-left-radius: 15px;
                border-bottom-right-radius: 15px;
                box-shadow: 0 2px 5px var(--shadow-color);
            }

            .sidebar-header {
                border-bottom: none;
                margin-bottom: 0;
                padding: 0;
            }

            .sidebar-nav {
                display: none; /* Sembunyikan navigasi di layar kecil, bisa diganti dengan hamburger menu */
            }

            .main-content {
                padding: 15px;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar h2 {
                margin-bottom: 10px;
            }

            .data-table th,
            .data-table td {
                padding: 8px 10px;
                font-size: 13px;
            }

            .status-button {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .sidebar-header h3 {
                font-size: 18px;
            }

            .data-table th,
            .data-table td {
                font-size: 12px;
            }

            .modal-content {
                padding: 20px;
            }

            .modal-content h3 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-user-circle icon"></i>
            <h3>Admin</h3>
            <div class="icons" style="margin-left: auto;">
                <i class="fas fa-th-large"></i> </div>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="#"><i class="fas fa-tachometer-alt icon"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-clipboard-list icon"></i> Input Pesanan</a></li>
                <li><a href="#" class="active"><i class="fas fa-shipping-fast icon"></i> Pengelolaan Pengiriman</a></li>
                <li><a href="#"><i class="fas fa-wallet icon"></i> Pengelolaan Pembayaran</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt icon"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="navbar">
            <h2>Boss Laundry</h2>
        </div>

        <h3 class="page-title">Data Pengambilan Pesanan</h3>

        <?php if (isset($message)) echo $message; ?>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No Pesanan</th>
                        <th>Nama Pemesan</th>
                        <th>Alamat</th>
                        <th>Pilihan Pengiriman</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data_pengiriman)): ?>
                        <?php foreach ($data_pengiriman as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['idPesanan']); ?></td>
                                <td><?php echo htmlspecialchars($row['namaPemesan']); ?></td>
                                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenisPengiriman']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($status); ?>  
                                    <a href="update_pengiriman.php?id=<?php echo htmlspecialchars($row['idPesanan']); ?>" class="status-button" style="text-decoration:none">
                                    <i class="fas fa-sync-alt icon"></i> Update
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada data pengiriman.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h3>Update Status Pengiriman</h3>
            <form method="POST" action="">
                <input type="hidden" id="modalNoPesanan" name="no_pesanan_update">
                <label for="newStatus">Pilih Status Baru:</label>
                <select id="newStatus" name="new_status">
                    <option value="Sedang Diproses">Sedang Diproses</option>
                    <option value="Siap Dikirim">Siap Dikirim</option>
                    <option value="Dalam Pengiriman">Dalam Pengiriman</option>
                    <option value="Sudah Diterima">Sudah Diterima</option>
                    <option value="Selesai">Selesai</option>
                </select>
                <button type="submit" name="update_status">Update Status</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript untuk Modal
        var updateModal = document.getElementById("updateModal");
        var modalNoPesanan = document.getElementById("modalNoPesanan");
        var newStatusSelect = document.getElementById("newStatus");

        function openModal(noPesanan, currentStatus) {
            modalNoPesanan.value = noPesanan;
            // Set nilai select berdasarkan status saat ini
            for (var i = 0; i < newStatusSelect.options.length; i++) {
                if (newStatusSelect.options[i].value === currentStatus) {
                    newStatusSelect.selectedIndex = i;
                    break;
                }
            }
            updateModal.style.display = "flex"; // Gunakan flex untuk centering
        }

        function closeModal() {
            updateModal.style.display = "none";
        }

        // Tutup modal jika user klik di luar area modal
        window.onclick = function(event) {
            if (event.target == updateModal) {
                closeModal();
            }
        }
    </script>
</body>
</html>