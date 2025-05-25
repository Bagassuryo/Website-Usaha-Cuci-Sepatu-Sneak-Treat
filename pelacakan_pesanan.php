<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['idAkun'])) {
    header("Location: login.php");
    exit();
}

$idAkun = $_SESSION['idAkun'];

$queryCustomer = "SELECT Customer_idCustomer FROM akun WHERE idAkun = ?";
$stmt = $conn->prepare($queryCustomer);
$stmt->bind_param("i", $idAkun);
$stmt->execute();
$resultCustomer = $stmt->get_result();

if ($resultCustomer->num_rows === 0) {
    echo "Data akun tidak ditemukan.";
    exit();
}

$rowCustomer = $resultCustomer->fetch_assoc();
$idCustomer = $rowCustomer['Customer_idCustomer'];

$queryPesanan = "
    SELECT * FROM pesanan 
    WHERE Customer_idCustomer = ? 
    AND Status_Pesanan IN ('Diproses', 'Selesai') 
    ORDER BY Tanggal_Pesanan DESC
";
$stmt = $conn->prepare($queryPesanan);
$stmt->bind_param("i", $idCustomer);
$stmt->execute();
$resultPesanan = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #888;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        h2 {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Daftar Pesanan Anda</h2>

<?php if ($resultPesanan->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>ID Pesanan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Total Harga</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $resultPesanan->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['idPesanan']); ?></td>
            <td><?= date('d-m-Y H:i', strtotime($row['Tanggal_Pesanan'])); ?></td>
            <td><?= htmlspecialchars($row['Status_Pesanan']); ?></td>
            <td>Rp<?= number_format($row['Total_Harga'], 0, ',', '.'); ?></td>
            <td><?= !empty($row['Catatan_Khusus']) ? htmlspecialchars($row['Catatan_Khusus']) : '-'; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p style="text-align: center;">Belum ada pesanan yang diproses atau selesai.</p>
<?php endif; ?>

</body>
</html>
