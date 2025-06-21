<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        .login-container {
            width: 90%;
            max-width: 400px;
            padding: 20px;
        }

        .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 16px;
        font-weight: 500;
        text-align: center; 
        }

        .alert.error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .alert.success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .alert.warning {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
            color: #1e1e1e;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #1e1e1e;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 30%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }

        button {
            width: 100%;
            background-color: #ffe100;
            border: none;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ffd500;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>LOGIN</h1>
    <?php
        if (isset($_GET['message'])) {
            if ($_GET['message'] == "failed") {
                echo "<div class='alert error'>Login gagal. Nomor ID atau password salah.</div>";
            } elseif ($_GET['message'] == "logout") {
                echo "<div class='alert success'>Anda telah berhasil logout.</div>";
            } elseif ($_GET['message'] == "belum_login") {
                echo "<div class='alert warning'>Anda harus login terlebih dahulu untuk mengakses sistem.</div>";
            }
        }
	?>

    <form action="proses_login.php" method="POST">
        <label for="idKurir">Masukkan nomor ID Anda!</label>
        <input type="text" id="idKurir" name="idKurir" required>

        <label for="password">Password</label>
        <div class="password-container">
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit">MASUK</button>
    </form>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.textContent = "🙈";
        } else {
            passwordField.type = "password";
            toggleIcon.textContent = "👁️";
        }
    }
</script>

</body>
</html>
