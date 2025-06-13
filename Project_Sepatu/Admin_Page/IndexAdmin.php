<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneak & Treat - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0f766e',
            secondary: '#115e59',
            accent: '#14b8a6',
            light: '#f0fdfa',
            dark: '#042f2e'
          }
        }
      }
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f3f4f6;
    }
    
    .sidebar {
      background: linear-gradient(180deg, #0f766e 0%, #115e59 100%);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .nav-item {
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
    }
    
    .nav-item:hover, .nav-item.active {
      background: rgba(255, 255, 255, 0.1);
      border-left: 3px solid #14b8a6;
    }
    
    .stat-card {
      transition: all 0.3s ease;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 5px;
      height: 100%;
    }
    
    .service-card {
      transition: all 0.3s ease;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
      border: 1px solid #e5e7eb;
    }
    
    .service-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      border-color: #14b8a6;
    }
    
    .service-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: #14b8a6;
    }
    
    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }
    
    .service-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }
    
    .floating-button {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      z-index: 50;
      transition: all 0.3s ease;
    }
    
    .floating-button:hover {
      transform: scale(1.1);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    
    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
      }
      .sidebar .nav-text {
        display: none;
      }
      .sidebar .logo-text {
        display: none;
      }
      .floating-button {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
      }
    }
  </style>
</head>
<body class="bg-gray-100">

<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../LoginAwal/login.php");
    exit();
}

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_sepatu";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Calculate total orders
$total_pesanan = 0;
$sql = "SELECT COUNT(idPesanan) AS total_pesanan FROM pesanan";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $total_pesanan = $row['total_pesanan'];
}

// Calculate active customers (waiting orders)
$pelanggan_aktif = 0;
$sql_pelanggan = "SELECT COUNT(DISTINCT p.Customer_idCustomer) AS total_aktif 
                  FROM pesanan p
                  JOIN customer c ON p.Customer_idCustomer = c.idCustomer
                  WHERE p.status_pesanan = 'menunggu'";
$result_pelanggan = $conn->query($sql_pelanggan);

if ($result_pelanggan && $row = $result_pelanggan->fetch_assoc()) {
    $pelanggan_aktif = $row['total_aktif'];
}

// Get active customers details
$sql_pelanggan_detail = "SELECT c.IdCustomer, c.Nama, c.No_Telepon, COUNT(p.idPesanan) as jumlah_pesanan
                         FROM customer c
                         JOIN pesanan p ON c.IdCustomer = p.Customer_idCustomer
                         WHERE p.status_pesanan = 'menunggu'
                         GROUP BY c.IdCustomer";
$result_pelanggan_detail = $conn->query($sql_pelanggan_detail);

// Calculate monthly income
$pendapatan_bulan_ini = 0;
$bulan_ini = date('Y-m');
$sql_pendapatan = "SELECT SUM(jumlah_pembayaran) AS total_pendapatan 
                   FROM pembayaran 
                   WHERE DATE_FORMAT(tanggal_pembayaran, '%Y-%m') = '$bulan_ini' 
                   AND status_pembayaran = 'lunas'";
$result_pendapatan = $conn->query($sql_pendapatan);

if ($result_pendapatan && $row = $result_pendapatan->fetch_assoc()) {
    $pendapatan_bulan_ini = $row['total_pendapatan'] ? $row['total_pendapatan'] : 0;
}

// Calculate previous month income for percentage
$bulan_lalu = date('Y-m', strtotime('-1 month'));
$pendapatan_bulan_lalu = 0;
$persentase = 0;

$sql_bulan_lalu = "SELECT SUM(jumlah_pembayaran) AS total_pendapatan 
                   FROM pembayaran 
                   WHERE DATE_FORMAT(tanggal_pembayaran, '%Y-%m') = '$bulan_lalu' 
                   AND status_pembayaran = 'lunas'";
$result_bulan_lalu = $conn->query($sql_bulan_lalu);

if ($result_bulan_lalu && $row = $result_bulan_lalu->fetch_assoc()) {
    $pendapatan_bulan_lalu = $row['total_pendapatan'] ? $row['total_pendapatan'] : 0;
}

if ($pendapatan_bulan_lalu > 0 && $pendapatan_bulan_ini > 0) {
    $persentase = (($pendapatan_bulan_ini - $pendapatan_bulan_lalu) / $pendapatan_bulan_lalu) * 100;
}

// Handle Add Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_layanan'])) {
    $nama = $conn->real_escape_string($_POST['nama_layanan']);
    $harga = (float)$_POST['harga'];
    $durasi_normal = $conn->real_escape_string($_POST['durasi_normal']);
    $durasi_express = $conn->real_escape_string($_POST['durasi_express']);

    $stmt = $conn->prepare("INSERT INTO Layanan (Nama_Layanan, Harga, Durasi_Pengerjaan, Durasi_Pengerjaan_express) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $nama, $harga, $durasi_normal, $durasi_express);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Layanan berhasil ditambahkan!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    header("Location: ../Admin_Page/indexAdmin.php");
    exit();
}

// Handle Delete Service
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM layanan WHERE idlayanan = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Layanan berhasil dihapus!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    header("Location: IndexAdmin.php");
    exit();
}

// Get Service Data
$result = $conn->query("SELECT * FROM Layanan ORDER BY idLayanan DESC");
?>

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 min-h-screen text-white transition-all duration-300">
      <div class="p-5 flex items-center gap-3 border-b border-white/10">
        <div class="bg-white p-2 rounded-lg">
          <i class="fas fa-shoe-prints text-primary text-xl"></i>
        </div>
        <h1 class="text-xl font-bold logo-text">Sneak&Treat</h1>
      </div>
      
      <nav class="p-4">
        <ul class="space-y-2">
          <li>
            <a href="IndexAdmin.php" class="nav-item active flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-chart-line"></i>
              <span class="nav-text">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="Pesanan/DeksP.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-shopping-bag"></i>
              <span class="nav-text">Pesanan</span>
            </a>
          </li>
          <li>
            <a href="Pelanggan/DeksPL.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-users"></i>
              <span class="nav-text">Pelanggan</span>
            </a>
          </li>
          <li>
            <a href="Pembayaran/DeksPM.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-credit-card"></i>
              <span class="nav-text">Pembayaran</span>
            </a>
          </li>
          <li>
            <a href="#" class="nav-item flex items-center gap-3 p-3 rounded-lg mt-8" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i>
              <span class="nav-text">Keluar</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="bg-white shadow-sm">
        <div class="flex justify-between items-center p-4">
          <div class="flex items-center">
            <button class="text-gray-500 mr-4 md:hidden">
              <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold text-gray-800">Dashboard Admin</h1>
          </div>
          
          <div class="flex items-center gap-4">
            <div class="relative">
              <button class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-bell text-xl"></i>
              </button>
              <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
            </div>
            
            <div class="flex items-center gap-3">
              <div class="bg-primary w-10 h-10 rounded-full flex items-center justify-center text-white font-bold">A</div>
              <div class="hidden md:block">
                <p class="font-medium">Admin</p>
                <p class="text-sm text-gray-500">Administrator</p>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 md:p-6">
        <!-- Dashboard Stats -->
        <section id="dashboard">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="stat-card bg-white p-5 shadow-sm">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-gray-500 font-medium">Total Pesanan</h3>
                <div class="bg-blue-100 p-2 rounded-lg">
                  <i class="fas fa-shopping-bag text-blue-600"></i>
                </div>
              </div>
              <p class="text-3xl font-bold text-gray-800"><?= $total_pesanan ?></p>
              <div class="mt-2 flex items-center text-sm text-green-600">
              </div>
            </div>
            
            <div class="stat-card bg-white p-5 shadow-sm">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-gray-500 font-medium">Pendapatan Bulan Ini</h3>
                <div class="bg-green-100 p-2 rounded-lg">
                  <i class="fas fa-money-bill-wave text-green-600"></i>
                </div>
              </div>
              <p class="text-3xl font-bold text-gray-800">Rp <?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?></p>
              <div class="mt-2 flex items-center text-sm <?= $pendapatan_bulan_ini > $pendapatan_bulan_lalu ? 'text-green-600' : ($pendapatan_bulan_ini < $pendapatan_bulan_lalu ? 'text-red-600' : 'text-gray-600') ?>">
                <?php if ($pendapatan_bulan_ini > 0 && $pendapatan_bulan_lalu > 0): ?>
                  <i class="fas fa-arrow-<?= $pendapatan_bulan_ini > $pendapatan_bulan_lalu ? 'up' : 'down' ?> mr-1"></i>
                  <span><?= round(abs($persentase), 2) ?>% <?= $pendapatan_bulan_ini > $pendapatan_bulan_lalu ? 'naik' : 'turun' ?> dari bulan lalu</span>
                <?php elseif ($pendapatan_bulan_ini > 0): ?>
                  <i class="fas fa-arrow-up mr-1"></i>
                  <span>Pendapatan bulan ini</span>
                <?php else: ?>
                  <i class="fas fa-info-circle mr-1"></i>
                  <span>Belum ada pendapatan</span>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="stat-card bg-white p-5 shadow-sm">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-gray-500 font-medium">Pelanggan Aktif</h3>
                <div class="bg-purple-100 p-2 rounded-lg">
                  <i class="fas fa-users text-purple-600"></i>
                </div>
              </div>
              <p class="text-3xl font-bold text-gray-800"><?= $pelanggan_aktif ?></p>
              <div class="mt-2 flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>Pelanggan dengan pesanan menunggu</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Active Customers Table -->
        <section id="active-customers" class="mb-12">
          <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
              <div>
                <h2 class="text-xl font-bold text-gray-800 mb-1">Detail Pelanggan Aktif</h2>
                <p class="text-gray-600">Pelanggan dengan pesanan dalam status menunggu</p>
              </div>
            </div>
            
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telp</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <?php if ($result_pelanggan_detail && $result_pelanggan_detail->num_rows > 0): ?>
                    <?php while ($customer = $result_pelanggan_detail->fetch_assoc()): ?>
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($customer['IdCustomer']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($customer['Nama']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($customer['No_Telepon']) ?></td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada pelanggan aktif</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['message'])): ?>
          <div class="p-4 mb-6 rounded-lg <?= $_SESSION['msg_type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <div class="flex items-center">
              <i class="fas <?= $_SESSION['msg_type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-3 text-lg"></i>
              <span><?= $_SESSION['message'] ?></span>
            </div>
          </div>
          <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <!-- Manajemen Layanan -->
        <section id="services" class="mb-12">
          <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
              <div>
                <h2 class="text-xl font-bold text-gray-800 mb-1">Manajemen Layanan</h2>
                <p class="text-gray-600">Kelola layanan yang tersedia di Sneak&Treat</p>
              </div>
              <button onclick="toggleForm()" class="mt-4 md:mt-0 bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Tambah Layanan</span>
              </button>
            </div>

            <!-- Form Tambah -->
            <div id="serviceForm" class="hidden bg-gray-50 p-5 rounded-lg mb-6 border border-gray-200">
              <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-gray-700 mb-2 font-medium">Nama Layanan</label>
                    <input type="text" name="nama_layanan" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                  </div>
                  <div>
                    <label class="block text-gray-700 mb-2 font-medium">Harga (Rp)</label>
                    <input type="number" name="harga" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                  </div>
                  <div>
                    <label class="block text-gray-700 mb-2 font-medium">Durasi Normal</label>
                    <input type="text" name="durasi_normal" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="contoh: 3 hari" required>
                  </div>
                  <div>
                    <label class="block text-gray-700 mb-2 font-medium">Durasi Express</label>
                    <input type="text" name="durasi_express" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="contoh: 1-3 hari" required>
                  </div>
                </div>
                <div class="flex gap-3 pt-2">
                  <button type="submit" class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-save"></i>
                    Simpan Layanan
                  </button>
                  <button type="button" onclick="toggleForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2.5 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-times"></i>
                    Batal
                  </button>
                </div>
              </form>
            </div>

            <!-- Daftar Layanan -->
            <div>
              <h3 class="text-lg font-medium text-gray-800 mb-4">Daftar Layanan Tersedia</h3>
              
              <?php if ($result->num_rows > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="service-card bg-white p-5">
                      <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                          <div class="service-icon bg-primary">
                            <i class="fas fa-concierge-bell"></i>
                          </div>
                          <h3 class="font-semibold text-lg text-gray-800"><?= htmlspecialchars($row['Nama_Layanan']) ?></h3>
                        </div>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">ID: <?= $row['idLayanan'] ?></span>
                      </div>
                      
                      <div class="mb-5">
                        <p class="text-primary font-bold text-xl">Rp <?= number_format($row['Harga'], 0, ',', '.') ?></p>
                      </div>
                      
                      <div class="grid grid-cols-2 gap-3 mb-5">
                        <div class="bg-blue-50 p-3 rounded-lg">
                          <p class="text-sm text-gray-600 mb-1">Normal</p>
                          <p class="font-medium"><?= htmlspecialchars($row['Durasi_Pengerjaan']) ?></p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                          <p class="text-sm text-gray-600 mb-1">Express</p>
                          <p class="font-medium"><?= htmlspecialchars($row['Durasi_Pengerjaan_express']) ?></p>
                        </div>
                      </div>
                      
                      <div class="flex justify-end">
                        <a href="indexAdmin.php?delete=<?= $row['idLayanan'] ?>" class="text-red-600 hover:text-red-800 flex items-center gap-2" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                          <i class="fas fa-trash"></i>
                          <span>Hapus</span>
                        </a>
                      </div>
                    </div>
                  <?php endwhile; ?>
                </div>
              <?php else: ?>
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-8 text-center">
                  <i class="fas fa-concierge-bell text-4xl text-gray-400 mb-4"></i>
                  <h4 class="text-gray-600 font-medium mb-2">Belum ada layanan tersedia</h4>
                  <p class="text-gray-500 mb-4">Tambahkan layanan baru untuk memulai</p>
                  <button onclick="toggleForm()" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Tambah Layanan
                  </button>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- Floating Action Button -->
  <button class="floating-button bg-primary text-white">
    <i class="fas fa-plus text-xl"></i>
  </button>

  <!-- Hidden logout form -->
  <form id="logout-form" action="../LoginAwal/logout.php" method="POST" style="display: none;">
  </form>

  <script>
    function toggleForm() {
      const form = document.getElementById('serviceForm');
      form.classList.toggle('hidden');
      
      if (!form.classList.contains('hidden')) {
        form.scrollIntoView({ behavior: 'smooth' });
      }
    }
    
    // Mobile sidebar toggle
    document.querySelector('[aria-label="Toggle sidebar"]').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('hidden');
      document.querySelector('.sidebar').classList.toggle('md:flex');
    });
  </script>
  
<?php 
// Close database connections
if (isset($result_pelanggan_detail)) {
    $result_pelanggan_detail->close();
}
$conn->close();
?>

</body>
</html>
