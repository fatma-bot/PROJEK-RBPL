<?php
session_start(); // Memulai sesi

// Hapus semua variabel sesi
$_SESSION = array();

// Jika ingin menghapus cookie sesi juga, hapus juga cookie sesi.
// Catatan: Ini akan menghancurkan sesi, dan bukan hanya data sesi!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan sesi
session_destroy();

// Arahkan pengguna kembali ke halaman login
header("Location: login.php?message=belum_login");
exit(); // Pastikan tidak ada kode lain yang dieksekusi setelah pengalihan
?>