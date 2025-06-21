 <?php
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';
$query = "SELECT idPesanan, namaPemesan, jenisPengiriman, totalharga, statusPesanan FROM pesanan";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Boss Laundry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #56B9F1;
            --light-bg: #f2f2f2;
            --white: #fff;
            --dark: #333;
            --button-purple: #a78bfa;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background-color: var(--primary-color);
            width: 220px;
            padding: 20px 0;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar .toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 12px 20px;
            color: white;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .sidebar ul li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li i {
            margin-right: 10px;
        }

        .sidebar ul li.active {
            background-color: white;
            color: black;
            border-radius: 10px;
        }

        .sidebar ul li.active i,
        .sidebar ul li.active a {
            color: black;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: inherit;
        }

        /* MAIN CONTENT */
       .main {
            flex-grow: 1;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .header h3 {
            color: var(--primary-color);
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f8f8;
        }

        .btn-edit, .btn-hapus {
            padding: 5px 12px;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-hapus {
            background-color: #dc3545;
            color: white;
        }

        .btn-hapus:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .main {
                margin-left: 0 !important;
                padding-left: 20px;
            }

            .toggle-btn {
                left: 10px !important;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li><i class="fas fa-user icon" style="color:black;"></i> <span class="label"><a href="profil.php" style="text-decoration: none; color:black;">Admin</a></span></li>
            <li class="active"><i class="fas fa-tachometer-alt icon"></i> <span class="label"><a href="index.php">Dashboard</a></span></li>
            <li><i class="fas fa-clipboard-list icon" style="color:black;"></i> <span class="label"><a href="input_pesanan.php" style="text-decoration: none; color:black;">Input Pesanan</a></span></li>
            <li><i class="fas fa-shipping-fast icon" style="color:black;"></i> <span class="label"><a href="pengiriman.php" style="text-decoration: none; color:black;">Pengelolaan Pengiriman</a></span></li>
            <li><i class="fas fa-wallet icon" style="color:black;"></i> <span class="label"><a href="pembayaran.php" style="text-decoration: none; color:black;">Pengelolaan Pembayaran</a></span></li>
            <li><i class="fas fa-sign-out-alt icon" style="color:black;"></i> <span class="label"><a href="logout.php" style="text-decoration: none; color:black;">Logout</a></span></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main" id="mainContent">
        <div class="header">
                <div>
                    <h1>Boss Laundry</h1>
                    <h3>Daftar Pesanan</h3>
                </div>
        </div>
        <table>
            <tr>
                <th>No Pesanan</th>
                <th>Nama Pemesan</th>
                <th>Pengiriman</th>
                <th>Jumlah Pembayaran</th>
                <th>Status Pesanan</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= 'P' . str_pad($row['idPesanan'], 5, '0', STR_PAD_LEFT); ?></td>
                <td><?= htmlspecialchars($row['namaPemesan']) ?></td>
                <td><?= htmlspecialchars($row['jenisPengiriman']) ?></td>
                <td><?= 'Rp ' . number_format($row['totalharga'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['statusPesanan']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['idPesanan'] ?>" class="btn-edit">✏️ edit</a>
                    <a href="hapus.php?id=<?= $row['idPesanan'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">🗑️ hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            const labels = document.querySelectorAll('.label');
            labels.forEach(label => {
                label.style.display = sidebar.classList.contains('collapsed') ? 'none' : 'inline';
            });
        }
    </script>
</body>

</html>
