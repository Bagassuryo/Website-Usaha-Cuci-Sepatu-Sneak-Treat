<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['idAkun'])) {
    header("Location: login.php");
    exit();
}

$idAkun = $_SESSION['idAkun'];

$stmt = $conn->prepare("SELECT Customer_idCustomer FROM akun WHERE idAkun = ?");
$stmt->bind_param("i", $idAkun);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "Data akun tidak ditemukan.";
    $stmt->close();
    $conn->close();
    exit();
}

$row = $res->fetch_assoc();
$idCustomer = $row['Customer_idCustomer'];
$stmt->close();

$queryPesanan = "
    SELECT p.*, pb.Status_Pembayaran, pb.Metode_Pembayaran, c.Nama as Nama_Customer
    FROM pesanan p 
    LEFT JOIN pembayaran pb ON p.idPesanan = pb.Pesanan_idPesanan
    LEFT JOIN customer c ON p.Customer_idCustomer = c.idCustomer
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
    <meta charset="UTF-8" />
    <title>Lihat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
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

        .status-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            max-width: 150px;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            width: fit-content;
            color: #fff;
        }

        /* Status Pesanan */
        .status-menunggu {
            background-color: #6c757d;
        }

        .status-diproses {
            background-color: #ffc107;
            color: #212529;
        }

        .status-selesai {
            background-color: #28a745;
        }

        .status-dibatalkan {
            background-color: #dc3545;
        }

        /* Status Pembayaran */
        .payment-menunggu {
            background-color: #dc3545;
        }

        .payment-lunas {
            background-color: #28a745;
        }

        .payment-gagal {
            background-color: #6c757d;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background-color: #ddd;
            margin-top: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            transition: width 1s ease-in-out;
        }

        .fill-menunggu {
            background-color: #6c757d;
            width: 25%;
        }

        .fill-diproses {
            background-color: #ffc107;
            width: 50%;
        }

        .fill-selesai {
            background-color: #28a745;
            width: 100%;
        }

        .fill-dibatalkan {
            background-color: #dc3545;
            width: 0%;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <header class="bg-emerald-800 shadow-lg">
        <div class="mx-auto flex h-16 max-w-screen-xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <img src="Logo.jpg" alt="Admin Logo" class="h-12 rounded-full">
                <span class="text-white text-xl font-bold">Lihat Pesanan</span>
            </div>

            <div class="flex items-center gap-6 text-white">
                <a class="block rounded-md bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600" href="logout.php"> Logout </a>
            </div>
        </div>
    </header>
    <div class="container">
        <h2>Lihat Pesanan Anda</h2>

        <?php if ($resultPesanan && $resultPesanan->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Status Pesanan</th>
                        <th>Status Pembayaran</th>
                        <th>Metode Pembayaran</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultPesanan->fetch_assoc()):
                        $statusPesanan = strtolower($row['Status_Pesanan'] ?? 'menunggu');
                        $statusPesananClass = '';
                        $statusPesananDisplay = '';

                        switch ($statusPesanan) {
                            case 'menunggu':
                                $statusPesananDisplay = 'Belum Dibayar';
                                $statusPesananClass = 'menunggu';
                                break;
                            case 'diproses':
                                $statusPesananDisplay = 'Proses Cuci';
                                $statusPesananClass = 'diproses';
                                break;
                            case 'selesai':
                                $statusPesananDisplay = 'Selesai';
                                $statusPesananClass = 'selesai';
                                break;
                            default:
                                $statusPesananDisplay = 'Belum Dibayar';
                                $statusPesananClass = 'menunggu';
                        }

                        $statusPembayaran = strtolower($row['Status_Pembayaran'] ?? 'menunggu');
                        $statusPembayaranClass = '';
                        $statusPembayaranDisplay = '';

                        switch ($statusPembayaran) {
                            case 'lunas':
                                $statusPembayaranDisplay = 'Lunas';
                                $statusPembayaranClass = 'payment-lunas';
                                break;
                            case 'gagal':
                                $statusPembayaranDisplay = 'Gagal';
                                $statusPembayaranClass = 'payment-gagal';
                                break;
                            case 'menunggu':
                            default:
                                $statusPembayaranDisplay = 'Belum Bayar';
                                $statusPembayaranClass = 'payment-menunggu';
                        }
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['idPesanan']); ?></td>
                            <td><?= $row['Tanggal_Pesanan'] ? date('d/m/Y H:i', strtotime($row['Tanggal_Pesanan'])) : '-'; ?></td>
                            <td>
                                <div class="status-wrapper">
                                    <span class="status status-<?= $statusPesananClass ?>">
                                        <?= $statusPesananDisplay; ?>
                                    </span>
                                    <div class="progress-bar">
                                        <div class="progress-fill fill-<?= $statusPesananClass ?>"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status <?= $statusPembayaranClass ?>">
                                    <?= $statusPembayaranDisplay ?>
                                </span>
                            </td>
                            <td><?= $row['Metode_Pembayaran'] ? htmlspecialchars($row['Metode_Pembayaran']) : '-'; ?></td>
                            <td>Rp<?= is_numeric($row['Total_Harga']) ? number_format($row['Total_Harga'], 0, ',', '.') : '-'; ?></td>
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
</body>

</html>
<?php
$stmt2->close();
$conn->close();
?>