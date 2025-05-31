<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $no_hp = $_POST["no_hp"];
    $jenis_sepatu = $_POST["jenis_sepatu"];
    $layanan = $_POST["layanan"];
    $tanggal = $_POST["tanggal"];

    echo "<h2>Terima kasih atas pemesanan Anda!</h2>";
    echo "Nama: $nama<br>";
    echo "No HP: $no_hp<br>";
    echo "Jenis Sepatu: $jenis_sepatu<br>";
    echo "Layanan: $layanan<br>";
    echo "Tanggal Antar: $tanggal<br>";

    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan Cuci Sepatu</title>
</head>
<body>

<h2>Form Pemesanan Cuci Sepatu</h2>

<form action="pemesanan.php" method="POST">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>No HP (WhatsApp):</label><br>
    <input type="text" name="no_hp" required><br><br>

    <label>Jenis Sepatu:</label><br>
    <select name="jenis_sepatu" required>
        <option value="">-- Pilih Jenis Sepatu --</option>
        <option value="Sneakers">Sneakers</option>
        <option value="Canvas">Canvas</option>
        <option value="Kulit">Kulit</option>
        <option value="Boots">Boots</option>
    </select><br><br>

    <label>Layanan Cuci:</label><br>
    <select name="layanan" required>
        <option value="">-- Pilih Layanan --</option>
        <option value="Reguler (2-3 hari)">Reguler (2-3 hari)</option>
        <option value="Express (1 hari)">Express (1 hari)</option>
        <option value="Deep Clean">Deep Clean</option>
    </select><br><br>

    <label>Tanggal Antar:</label><br>
    <input type="date" name="tanggal" required><br><br>

    <button type="submit">Kirim Pesanan</button>
</form>

</body>
</html>
