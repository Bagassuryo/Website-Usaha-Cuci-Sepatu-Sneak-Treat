<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['idAkun'])) {
    header("Location: login.php");
    exit();
}

$idAkun = $_SESSION['idAkun'];

// Ambil idCustomer dari akun
$queryCustomer = "SELECT Customer_idCustomer FROM akun WHERE idAkun = ?";
$stmt1 = $conn->prepare($queryCustomer);
$stmt1->bind_param("i", $idAkun);
$stmt1->execute();
$resultCustomer = $stmt1->get_result();

if ($resultCustomer->num_rows === 0) {
    echo "Data akun tidak ditemukan.";
    $stmt1->close();
    $conn->close();
    exit();
}

$rowCustomer = $resultCustomer->fetch_assoc();
$idCustomer = $rowCustomer['Customer_idCustomer'];
$stmt1->close();

// Ambil daftar pesanan dan status pembayaran
$queryPesanan = "
    SELECT p.*, pb.Status_Pembayaran, pb.Metode_Pembayaran
    FROM pesanan p 
    LEFT JOIN pembayaran pb ON p.idPesanan = pb.Pesanan_idPesanan
    WHERE p.Customer_idCustomer = ? 
    ORDER BY p.Tanggal_Pesanan DESC
";
$stmt2 = $conn->prepare($queryPesanan);
$stmt2->bind_param("i", $idCustomer);
$stmt2->execute();
$resultPesanan = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan</title>
    <style>
        /* Styles tetap sama seperti sebelumnya... */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .status-menunggu { background-color: #fff3cd; color: #856404; }
        .status-diproses { background-color: #cce5ff; color: #0056b3; }
        .status-selesai { background-color: #d4edda; color: #155724; }
        .status-dibatalkan { background-color: #f8d7da; color: #721c24; }
        .status-belum-bayar { background-color: #f8d7da; color: #721c24; }
        .status-lunas { background-color: #d4edda; color: #155724; }
        .status-gagal { background-color: #f8d7da; color: #721c24; }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .detail-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .detail-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Daftar Pesanan Anda</h2>

    <?php if ($resultPesanan->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Status Pesanan</th>
                <th>Status Pembayaran</th>
                <th>Metode Pembayaran</th>
                <th>Total Harga</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultPesanan->fetch_assoc()): 
                $statusPesanan = strtolower($row['Status_Pesanan']);
                $statusPembayaran = strtolower($row['Status_Pembayaran'] ?? 'belum-bayar');
            ?>
            <tr>
                <td>#<?= htmlspecialchars($row['idPesanan']); ?></td>
                <td><?= $row['Tanggal_Pesanan'] ? date('d/m/Y H:i', strtotime($row['Tanggal_Pesanan'])) : '-'; ?></td>
                <td>
                    <span class="status status-<?= $statusPesanan ?>">
                        <?= htmlspecialchars($row['Status_Pesanan']); ?>
                    </span>
                </td>
                <td>
                    <span class="status status-<?= $statusPembayaran ?>">
                        <?= $row['Status_Pembayaran'] ? htmlspecialchars($row['Status_Pembayaran']) : 'Belum Bayar'; ?>
                    </span>
                </td>
                <td><?= $row['Metode_Pembayaran'] ? htmlspecialchars($row['Metode_Pembayaran']) : '-'; ?></td>
                <td>Rp<?= is_numeric($row['Total_Harga']) ? number_format($row['Total_Harga'], 0, ',', '.') : '-'; ?></td>
                <td><?= !empty($row['Catatan_Khusus']) ? htmlspecialchars($row['Catatan_Khusus']) : '-'; ?></td>
                <td>
                    <button class="detail-btn" onclick="lihatDetail(<?= $row['idPesanan']; ?>)">Detail</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="no-data">
            <p>Belum ada pesanan.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function lihatDetail(idPesanan) {
    window.location.href = 'detail_pesanan.php?id=' + idPesanan;
}
</script>
</body>
</html>

<?php
$stmt2->close();
$conn->close();
?>
