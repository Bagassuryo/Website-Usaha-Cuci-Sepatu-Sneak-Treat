<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginAwal/login.php");
    exit();
}

// Database connection
require_once 'db_config.php';

// Initialize variables
$error = '';

// Get user data
$stmt = $conn->prepare("SELECT c.IdCustomer, c.Nama, c.No_Telepon, a.Email_Akun, a.idAkun
                       FROM customer c
                       JOIN akun a ON c.IdCustomer = a.Customer_IdCustomer
                       WHERE a.idAkun = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

// If data not found
if (!$user_data) {
    $error = "Data pengguna tidak ditemukan!";
    header("Location: ../LoginAwal/login.php");
    exit();
}

// Get customer ID
$idCustomer = $user_data['IdCustomer'];

// Query to get orders
$queryPesanan = "
    SELECT p.*, pb.Status_Pembayaran, pb.Metode_Pembayaran, l.Nama_Layanan
    FROM pesanan p 
    LEFT JOIN pembayaran pb ON p.idPesanan = pb.Pesanan_idPesanan
    LEFT JOIN layanan l ON p.idLayanan = l.idLayanan
    WHERE p.Customer_IdCustomer = ?
    ORDER BY p.Tanggal_Pesanan DESC
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan | Sneak & Treat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .form-input {
      transition: all 0.3s;
    }
    .form-input:focus {
      border-color: #fbbf24;
      box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
    }
    .error-message {
      color: #ef4444;
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }
    /* Status Styles */
    .status-wrapper {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
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
    /* Order Status */
    .status-menunggu { background-color: #6c757d; }
    .status-diproses { background-color: #ffc107; color: #212529; }
    .status-selesai { background-color: #28a745; }
    .status-dibatalkan { background-color: #dc3545; }
    /* Payment Status */
    .payment-menunggu { background-color: #dc3545; }
    .payment-lunas { background-color: #28a745; }
    .payment-gagal { background-color: #6c757d; }
    /* Progress Bar */
    .progress-bar {
      width: 100%;
      height: 8px;
      border-radius: 4px;
      background-color: #e5e7eb;
      margin-top: 6px;
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      transition: width 1s ease-in-out;
    }
    .fill-menunggu { background-color: #6c757d; width: 25%; }
    .fill-diproses { background-color: #ffc107; width: 50%; }
    .fill-selesai { background-color: #28a745; width: 100%; }
    .fill-dibatalkan { background-color: #dc3545; width: 0%; }
    /* Table Styles */
    .order-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    .order-table th, 
    .order-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #e5e7eb;
    }
    .order-table th {
      background-color: #f3f4f6;
      font-weight: 600;
      color: #374151;
    }
    .order-table tr:hover {
      background-color: #f9fafb;
    }
    .no-data {
      text-align: center;
      padding: 40px;
      color: #6b7280;
      font-size: 1.125rem;
    }
  </style>
</head>
<body class="bg-emerald-800 text-white font-sans">

  <header class="bg-emerald-800">
    <div class="mx-auto flex h-16 max-w-screen-xl items-center gap-8 px-4 sm:px-6 lg:px-8">
      <a class="block text-teal-600 dark:text-teal-300" href="../Landing_page/index.php">
        <span class="sr-only">Home</span>
        <img src="logo.jpg" alt="Logo" class="h-12 rounded-full">
      </a>

      <div class="flex flex-1 items-center justify-end md:justify-between">
        <nav aria-label="Global" class="hidden md:block">
          <ul class="flex items-center gap-6 text-sm">
            <li> <a class="text-white transition hover:text-yellow-400" href="Pemesanan.php"> Buat Pesanan </a> </li>
          </ul>
        </nav>

        <div class="flex items-center gap-4">
          <div class="sm:flex sm:gap-4">
            <?php if(isset($_SESSION['user_id'])): ?>
              <span class="text-white px-4 py-2"><?php echo htmlspecialchars($user_data['Nama'] ?? 'Pengguna'); ?></span>
              <a class="block rounded-md bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600" 
                 href="../LoginAwal/logout.php"> Logout </a>
            <?php else: ?>
              <a class="block rounded-md bg-yellow-400 px-5 py-2.5 text-sm font-medium text-black transition hover:bg-yellow-300" 
                 href="../LoginAwal/login.php"> Login </a>
              <a class="hidden rounded-md bg-white px-5 py-2.5 text-sm font-medium text-black transition hover:bg-black hover:text-white sm:block" 
                 href="../LoginAwal/register.php"> Register </a>
            <?php endif; ?>
          </div>

          <!-- Mobile menu button -->
          <button class="block rounded-sm bg-gray-100 p-2.5 text-gray-600 transition hover:text-gray-600/75 md:hidden dark:bg-gray-800 dark:text-white dark:hover:text-white/75">
            <span class="sr-only">Toggle menu</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Order History Section -->
  <section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-yellow-300 mb-4">
          <i class="fas fa-history mr-2"></i>Riwayat Pesanan
        </h1>
        <p class="text-gray-300 max-w-2xl mx-auto">
          Halo <strong><?php echo htmlspecialchars($user_data['Nama'] ?? 'Pelanggan'); ?></strong>! 
          Berikut adalah riwayat pesanan Anda di Sneak & Treat.
        </p>
        
        <?php if (!empty($error)): ?>
          <div class="mt-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <p><?php echo $error; ?></p>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 py-6 px-8">
          <h2 class="text-2xl font-bold text-white">
            <i class="fas fa-clipboard-list mr-2"></i>Daftar Pesanan Anda
          </h2>
        </div>
        
        <div class="p-8 text-gray-800">
          <?php if ($resultPesanan && $resultPesanan->num_rows > 0): ?>
            <div class="overflow-x-auto">
              <table class="order-table">
                <thead>
                  <tr>
                    <th>ID Pesanan</th>
                    <th>Layanan</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Antar</th>
                    <th>Status Pesanan</th>
                    <th>Status Pembayaran</th>
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
                        $statusPesananDisplay = 'Menunggu';
                        $statusPesananClass = 'menunggu';
                        break;
                      case 'diproses':
                        $statusPesananDisplay = 'Diproses';
                        $statusPesananClass = 'diproses';
                        break;
                      case 'selesai':
                        $statusPesananDisplay = 'Selesai';
                        $statusPesananClass = 'selesai';
                        break;
                      case 'dibatalkan':
                        $statusPesananDisplay = 'Dibatalkan';
                        $statusPesananClass = 'dibatalkan';
                        break;
                      default:
                        $statusPesananDisplay = 'Menunggu';
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
                      <td class="font-medium">#<?= htmlspecialchars($row['idPesanan']); ?></td>
                      <td><?= htmlspecialchars($row['Nama_Layanan'] ?? '-'); ?></td>
                      <td><?= $row['Tanggal_Pesanan'] ? date('d/m/Y H:i', strtotime($row['Tanggal_Pesanan'])) : '-'; ?></td>
                      <td><?= $row['Tanggal_Antar'] ? date('d/m/Y', strtotime($row['Tanggal_Antar'])) : '-'; ?></td>
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
                      <td class="font-semibold">Rp<?= is_numeric($row['Total_harga']) ? number_format($row['Total_harga'], 0, ',', '.') : '-'; ?></td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="no-data">
              <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
              <p>Belum ada pesanan yang dibuat.</p>
              <a href="Pemesanan.php" class="mt-4 inline-block px-6 py-2 bg-yellow-400 text-black rounded-lg hover:bg-yellow-500 transition">
                Buat Pesanan Sekarang
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <footer class="bg-emerald-800 text-white py-10 text-center">
    <p class="mb-4">&copy; 2025 Sneak & Treat. All rights reserved.</p>
    <div class="flex justify-center space-x-6">
      <a href="https://linktr.ee/sneakandtreat?fbclid=PAZXh0bgNhZW0CMTEAAadVlw7MkI0uRAENVoK80kG26txNXvdgK2F_rt6iZgbfhnvdjkR4IkMq12wiHA_aem_tSmAhZMSX-O4rsGkNZNV9w" class="hover:text-yellow-400">LinkTree🍀</a>
    </div>
  </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>