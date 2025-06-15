<?php
//session_start();
require 'koneksi.php';

// Ambil data pembayaran dari database
$sql = "SELECT idPesanan, namaPemesan, alamat, totalharga FROM pesanan";
$result = $connect->query($sql);

$data_pembayaran = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_pembayaran[] = $row; 
        $idPesanan = $row['idPesanan'];
        $sql = "SELECT * FROM pembayaran WHERE idPesanan = '$idPesanan'";
        $query = mysqli_query($connect, $sql);
        $data = mysqli_fetch_array($query);
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laundry Clean</title>
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

        .sidebar h3 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .btn-update {
            background-color: var(--button-purple);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-update:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            td {
                padding-left: 50%;
                position: relative;
            }

            td::before {
                position: absolute;
                top: 12px;
                left: 12px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
            }

            td:nth-of-type(1)::before {
                content: "No Pesanan";
            }

            td:nth-of-type(2)::before {
                content: "Nama Pemesan";
            }

            td:nth-of-type(3)::before {
                content: "Alamat";
            }

            td:nth-of-type(4)::before {
                content: "Pilihan pembayaran";
            }

            td:nth-of-type(5)::before {
                content: "Status";
            }
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li><i class="fas fa-user icon" style="color:black;"></i> <span class="label"><a href="#" style="text-decoration: none; color:black;">Admin</a></span></li>
            <li><i class="fas fa-tachometer-alt icon" style="color:black;"></i> <span class="label"><a href="#" style="text-decoration: none; color:black;">Dashboard</a></span></li>
            <li><i class="fas fa-clipboard-list icon" style="color:black;"></i> <span class="label"><a href="#" style="text-decoration: none; color:black;">Input Pesanan</a></span></li>
            <li><i class="fas fa-shipping-fast icon" style="color:black;"></i> <span class="label"><a href="pengiriman.php" style="text-decoration: none; color:black;">Pengelolaan pembayaran</a></span></li>
            <li style="background-color:white; color:black; border-radius:10px;"><i class="fas fa-wallet icon" style="color:black;"></i> <span class="label"><a href="pembayaran.php" style="text-decoration: none; color:black;">Pengelolaan Pembayaran</a></span></li>
            <li><i class="fas fa-sign-out-alt icon" style="color:black;"></i> <span class="label"><a href="logout.php" style="text-decoration: none; color:black;">Logout</a></span></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">
            <div>
                <h1>Boss Laundry</h1>
                <h3>Data Pembayaran Pesanan</h3>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No Pesanan</th>
                    <th>Nama Pemesan</th>
                    <th>Alamat</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($data_pembayaran)) {
                    foreach ($data_pembayaran as $row) { ?>
                        <tr>
                            <td><?php echo $row['idPesanan']; ?></td>
                            <td><?php echo $row['namaPemesan']; ?></td>
                            <td><?php echo $row['alamat']; ?></td>
                            <td><?php echo $row['totalharga']; ?></td>
                            <td><?php 
                                $idPesanan = $row['idPesanan'];
                                $sql2 = "SELECT * FROM pembayaran WHERE idPesanan = '$idPesanan'";
                                $query2 = mysqli_query($connect, $sql2);
                                $dataPembayaran = mysqli_fetch_array($query2);
                                $tanggalbayar = $dataPembayaran['tglPembayaran'];
                                echo $tanggalbayar; ?>
                            </td>
                            <td> <?php
                                $idPesanan = $row['idPesanan'];
                                $sql2 = "SELECT * FROM pembayaran WHERE idPesanan = '$idPesanan'";
                                $query2 = mysqli_query($connect, $sql2);
                                $dataPembayaran = mysqli_fetch_array($query2);
                                $statusbayar = $dataPembayaran['statusPembayaran'];
                                if($statusbayar == "belum bayar") {?>
                                    <a href="update_pembayaran.php?id=<?php echo $idPesanan; ?>" class="status-button" style="text-decoration: none">
                                    <i class="fas fa-sync-alt icon"></i> Update
                                    </a> <?php
                                }
                                else {
                                    echo "dibayar";
                                } ?>
                            </td>
                        </tr>
                    <?php
                    }
                }  
                else {
                    echo "<tr><td colspan='6'>Tidak ada data pembayaran.</td></tr>";
                }
                ?>
            </tbody>
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