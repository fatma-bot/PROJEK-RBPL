<?php
session_start();
require 'koneksi.php';

if (empty($_SESSION['noHp'])) {
    header('Location: login.php?message=belum_login');
    exit();
}

$noHp = $_SESSION['noHp'];
$sql = "SELECT * FROM pelanggan WHERE noHp = '$noHp'";
$result = mysqli_query($connect, $sql);
$user = mysqli_fetch_assoc($result);

$default_foto = "image/user.png";
$foto_profil = $user['foto'] ?: $default_foto;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $hp = mysqli_real_escape_string($connect, $_POST['noHp']);

    // Cek apakah ingin reset ke default
    if (isset($_POST['reset_foto'])) {
        $foto_profil = $default_foto;
    } elseif ($_FILES['foto']['name']) {
        $nama_file = uniqid() . '_' . $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp, 'uploads/' . $nama_file);
        $foto_profil = $nama_file;
    }

    // Update database
    $update = "UPDATE pelanggan SET namaPelanggan='$nama', noHp='$hp', foto='$foto_profil' WHERE noHp='$noHp'";
    if (mysqli_query($connect, $update)) {
        $_SESSION['noHp'] = $hp; // Update session jika noHp diganti
        header("Location: profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #fff;
            padding: 20px;
            text-align: center;
        }
        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            text-decoration: none;
            color: #333;
        }
        .container {
            max-width: 400px;
            margin: auto;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            object-fit: cover;
        }
        .form-group {
            background: #f4f7fe;
            margin: 15px 0;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        .form-group i {
            margin-right: 10px;
        }
        input[type="text"], input[type="file"] {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1rem;
        }
        .btn {
            width: 100%;
            background: #3D4EB0;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }
        .btn:hover {
            background: #2c3c8f;
        }
        .reset-btn {
            font-size: 0.9rem;
            color: #999;
            background: none;
            border: none;
            text-decoration: underline;
            cursor: pointer;
        }
        @media (max-width: 480px) {
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            input[type="text"] {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <a class="back-arrow" href="profile.php">&#8592;</a>

    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <img src="uploads/<?php echo htmlspecialchars($foto_profil); ?>" class="profile-img" alt="foto profil"><br>
            <label for="foto">ubah foto profil</label><br> <br>
            <input type="file" name="foto" accept="image/*">
            <br>
            <button type="submit" name="reset_foto" class="reset-btn">Hapus dan gunakan foto default</button>

            <div class="form-group">
                <i class="fas fa-user-edit"></i>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($user['namaPelanggan']); ?>" required>
            </div>

            <div class="form-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="noHp" value="<?php echo htmlspecialchars($user['noHp']); ?>" required>
            </div>

            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
