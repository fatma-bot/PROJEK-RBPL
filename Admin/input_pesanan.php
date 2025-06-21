<?php 
session_start();
if (!isset($_SESSION['idAdmin'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Input Pesanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            position: relative;
        }
        .close-btn {
        position: absolute;
        top: 20px;
        right: 30px;
        background-color:#f2f2f7;
        border: none;
        font-size: 28px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        text-decoration: none;
        transition: color 0.2s ease;
        }
        .close-btn:hover {
            color: #e74c3c;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        label {
            font-weight: 600;
            display: block;
            margin-top: 20px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .pakaian-item {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .pakaian-item select, .pakaian-item input {
            margin-right: 10px;
            flex: 1;
        }
        .add-btn {
            margin-top: 10px;
            padding: 8px 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .submit-btn {
            width: 100%;
            margin-top: 30px;
            padding: 12px;
            background: #2ecc71;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php" class="close-btn">&times;</a>
    <h2>Form Pemesanan</h2>
    <form action="simpan_pesanan.php" method="POST" id="formPesanan">
        <label>Nama Pemesan</label>
        <input type="text" name="namaPemesan" required>
        
        <label>Alamat</label>
        <input type="text" name="alamat" required>

        <label>Jenis Pengiriman</label>
        <select name="jenisPengiriman" required>
            <option value="">-- Pilih --</option>
            <option value="Ambil sendiri">Ambil sendiri</option>
            <option value="Kurir">Kurir</option>
        </select>

        <label>Jenis Layanan</label>
        <select name="jenisPesanan" id="jenisPesanan" required>
            <option value="">-- Pilih --</option>
            <option value="Cuci Kering">Cuci Kering</option>
            <option value="Cuci Setrika">Cuci Setrika</option>
        </select>

        <label>Jenis Pakaian & Jumlah</label>
        <div id="pakaianContainer">
            <div class="pakaian-item">
                <select name="jenisPakaian[]" required>
                    <option value="Jaket">Jaket</option>
                    <option value="Kemeja">Kemeja</option>
                    <option value="Terusan / dress">Terusan / dress</option>
                    <option value="Celana / Rok">Celana / Rok</option>
                </select>
                <input type="number" name="jumlah[]" placeholder="Jumlah" required>
            </div>
        </div>
        <button type="button" class="add-btn" onclick="tambahPakaian()">+ Tambah Pakaian</button>

        <button type="submit" class="submit-btn">Simpan Pesanan</button>
    </form>
</div>

<script>
    function tambahPakaian() {
        const container = document.getElementById('pakaianContainer');
        const div = document.createElement('div');
        div.className = 'pakaian-item';
        div.innerHTML = `
            <select name="jenisPakaian[]" required>
                <option value="Jaket">Jaket</option>
                <option value="Kemeja">Kemeja</option>
                <option value="Terusan / dress">Terusan / dress</option>
                <option value="Celana / Rok">Celana / Rok</option>
            </select>
            <input type="number" name="jumlah[]" placeholder="Jumlah" required>
        `;
        container.appendChild(div);
    }
</script>
</body>
</html>
