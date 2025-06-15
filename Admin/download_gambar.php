<?php
// Pastikan tidak ada spasi atau karakter di luar tag PHP pembuka/penutup
// agar tidak ada output header yang tidak diinginkan sebelum pengaturan header()

if (isset($_GET['file'])) {
    $fileName = basename(urldecode($_GET['file'])); // Ambil nama file dari URL, decode, dan bersihkan
    $filePath = 'uploads/' . $fileName; // Path lengkap ke file gambar di server

    // --- Validasi Keamanan Penting ---
    // Pastikan file berada di dalam direktori yang diizinkan
    // Ini mencegah "Path Traversal" di mana user bisa mencoba mendownload file sensitif
    // dengan mengirimkan nama_file seperti "../../etc/passwd"
    $realFilePath = realpath($filePath);
    $uploadPath = realpath('uploads/');

    if ($realFilePath && strpos($realFilePath, $uploadPath) === 0 && file_exists($realFilePath)) {
        // Tentukan Content-Type berdasarkan tipe file (opsional tapi bagus)
        // Ini akan memberitahu browser bagaimana cara menangani file
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
                $contentType = 'image/jpeg';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            case 'gif':
                $contentType = 'image/gif';
                break;
            // Tambahkan tipe lain jika diperlukan
            default:
                $contentType = 'application/octet-stream'; // Default untuk tipe yang tidak dikenal
        }

        // Atur header HTTP untuk unduhan
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"'); // "attachment" memaksa download
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($realFilePath)); // Ukuran file

        // Kosongkan buffer output PHP dan server web (penting untuk file besar)
        ob_clean();
        flush();

        // Baca file dan kirimkan ke output
        readfile($realFilePath);
        exit;
    } else {
        // File tidak ditemukan atau lokasi tidak valid (upaya keamanan)
        header("HTTP/1.0 404 Not Found");
        echo "Error: File tidak ditemukan atau tidak valid.";
        exit;
    }
} else {
    // Parameter 'file' tidak ada
    header("HTTP/1.0 400 Bad Request");
    echo "Error: Nama file tidak diberikan.";
    exit;
}
?>