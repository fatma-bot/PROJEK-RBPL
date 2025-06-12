<?php
session_start();
require 'koneksi.php';

if(empty($_SESSION['noHp'])) {
    header('location:login.php?message=belum_login');
    exit();
}

$noHp = $_SESSION['noHp'];

// Query untuk mengambil semua notifikasi user, yang belum dibaca di atas
$sql_notifikasi = "SELECT * FROM notifikasi WHERE noHp = '$noHp' ORDER BY is_read ASC, created_at DESC";
$query_notifikasi = mysqli_query($connect, $sql_notifikasi);

// Setelah notifikasi ditampilkan, kita bisa menandainya sebagai sudah dibaca
// Ini bisa dilakukan via AJAX atau langsung di halaman ini setelah fetching
// Untuk kesederhanaan, kita akan menandai semua notifikasi yang ditampilkan sebagai 'dibaca'
$sql_mark_as_read = "UPDATE notifikasi SET is_read = TRUE WHERE noHp = '$noHp' AND is_read = FALSE";
mysqli_query($connect, $sql_mark_as_read); // Jalankan tanpa perlu cek error untuk kesederhanaan

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Anda</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; }
        .container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            overflow: hidden;
            color: #333;
            padding: 20px;
        }
        h1 {
            color: #4A90E2;
            text-align: center;
            margin-bottom: 30px;
        }
        .notification-item {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #fdfdfd;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .notification-item.unread {
            background-color: #e6f0ff; /* Latar belakang berbeda untuk notifikasi belum dibaca */
            font-weight: bold;
            border-color: #4A90E2;
        }
        .notification-message {
            font-size: 1em;
            margin-bottom: 5px;
        }
        .notification-time {
            font-size: 0.8em;
            color: #777;
            text-align: right;
        }
        .no-notifikasi {
            text-align: center;
            color: #777;
            padding: 30px;
            font-size: 1.1em;
        }
        .back-button {
            display: block;
            width: fit-content;
            margin: 20px auto 0;
            background-color: #4A90E2;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #3a7bd5;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notifikasi Anda</h1>

        <?php
        if (mysqli_num_rows($query_notifikasi) > 0) {
            while ($notif = mysqli_fetch_assoc($query_notifikasi)) {
                $isUnreadClass = $notif['is_read'] ? '' : 'unread';
                ?>
                <div class="notification-item <?php echo $isUnreadClass; ?>">
                    <div class="notification-message">
                        <?php echo htmlspecialchars($notif['message']); ?>
                    </div>
                    <?php if ($notif['order_id']): ?>
                        <div class="notification-time">
                            <a href="riwayat_pesanan.php#order-<?php echo htmlspecialchars($notif['order_id']); ?>">Lihat Pesanan #<?php echo htmlspecialchars($notif['order_id']); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="notification-time">
                        <?php echo date('d M Y H:i', strtotime($notif['created_at'])); ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='no-notifikasi'>Anda belum memiliki notifikasi.</p>";
        }
        ?>
        <a href="dashboard.php" class="back-button">Kembali ke Dashboard</a>
    </div>
</body>
</html>
<?php mysqli_close($connect); ?>