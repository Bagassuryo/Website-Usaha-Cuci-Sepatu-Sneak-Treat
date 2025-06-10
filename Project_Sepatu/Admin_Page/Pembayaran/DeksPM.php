<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneak & Treat - Manajemen Pembayaran</title>
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
    
    /* Table styling */
    .table-container {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .table-header {
      background-color: #0f766e;
      color: white;
    }
    
    .table-row:hover {
      background-color: #f0fdfa;
    }
    
    .status-badge {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
      display: inline-block;
    }
    
    .status-lunas {
      background-color: #dcfce7;
      color: #16a34a;
    }
    
    .status-pending {
      background-color: #fef3c7;
      color: #d97706;
    }
    
    .status-gagal {
      background-color: #fee2e2;
      color: #dc2626;
    }
  </style>
</head>
<body class="bg-gray-100">
<?php
session_start();
include('conn.php');

// Generate CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Proses update status jika ada request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }
    
    $id_pembayaran = $_POST['id_pembayaran'];
    $new_status = $_POST['new_status'];
    
    // Validasi input
    $allowed_statuses = ['Lunas', 'Pending', 'Gagal'];
    if (!in_array($new_status, $allowed_statuses)) {
        die("Status tidak valid");
    }
    
    // Update status di database
    $stmt = $conn->prepare("UPDATE pembayaran SET Status_Pembayaran = ? WHERE IdPembayaran = ?");
    $stmt->bind_param("si", $new_status, $id_pembayaran);
    
    if ($stmt->execute()) {
        $success_message = "Status pembayaran berhasil diupdate";
    } else {
        $error_message = "Gagal mengupdate status pembayaran: " . $conn->error;
    }
    
    $stmt->close();
    
    // Redirect untuk menghindari resubmission
    header("Location: DeksPM.php");
    exit();
}

$sql = "SELECT 
            IdPembayaran,
            Metode_Pembayaran,
            Jumlah_Pembayaran,
            Status_Pembayaran,
            Tanggal_Pembayaran,
            Pesanan_IdPesanan
        FROM pembayaran
        ORDER BY Tanggal_Pembayaran DESC";

$result = $conn->query($sql);
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
            <a href="../IndexAdmin.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-chart-line"></i>
              <span class="nav-text">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="../Pesanan/DeksP.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-shopping-bag"></i>
              <span class="nav-text">Pesanan</span>
            </a>
          </li>
          <li>
            <a href="../Pelanggan/DeksPL.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-users"></i>
              <span class="nav-text">Pelanggan</span>
            </a>
          </li>
          <li>
            <a href="DeksPM.php" class="nav-item active flex items-center gap-3 p-3 rounded-lg">
              <i class="fas fa-credit-card"></i>
              <span class="nav-text">Pembayaran</span>
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
            <h1 class="text-xl font-bold text-gray-800">Manajemen Pembayaran</h1>
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
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
          <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Daftar Pembayaran</h2>
            <p class="text-gray-600">Kelola semua pembayaran pelanggan Sneak&Treat</p>
          </div>
        </div>

        <!-- Order Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-container">
              <table class="min-w-full">
                <thead>
                  <tr class="table-header">
                    <th class="px-6 py-3 text-left text-sm font-medium">ID Pembayaran</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Metode</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Jumlah</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="table-row">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= htmlspecialchars($row['IdPembayaran']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($row['Metode_Pembayaran']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp <?= number_format($row['Jumlah_Pembayaran'], 0, ',', '.') ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <?php 
                          $statusClass = 'status-' . strtolower($row['Status_Pembayaran']);
                          $statusText = htmlspecialchars($row['Status_Pembayaran']);
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                          <?= $statusText ?>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= date('d/m/Y H:i', strtotime($row['Tanggal_Pembayaran'])) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($row['Pesanan_IdPesanan']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="openStatusModal(<?= $row['IdPembayaran'] ?>, '<?= $row['Status_Pembayaran'] ?>')" 
                                class="text-accent hover:text-secondary transition-colors">
                          <i class="fas fa-edit"></i> Ubah Status
                        </button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-12 text-center">
              <i class="fas fa-credit-card text-4xl text-gray-400 mb-4"></i>
              <h4 class="text-gray-600 font-medium mb-2">Belum ada pembayaran</h4>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Update Status -->
  <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Ubah Status Pembayaran</h3>
        <button onclick="closeStatusModal()" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <form id="statusForm" method="POST" action="DeksPM.php">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="update_status" value="1">
        <input type="hidden" id="id_pembayaran" name="id_pembayaran">
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="new_status">
            Pilih Status Baru
          </label>
          <select id="new_status" name="new_status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
            <option value="Lunas">Lunas</option>
            <option value="Pending">Pending</option>
            <option value="Gagal">Gagal</option>
          </select>
        </div>
        
        <div class="flex justify-end gap-3">
          <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Batal
          </button>
          <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Mobile sidebar toggle
    document.querySelector('button[aria-label="Toggle sidebar"]')?.addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('hidden');
      document.querySelector('.sidebar').classList.toggle('md:flex');
    });
    
    // Fungsi untuk modal status
    function openStatusModal(id, currentStatus) {
      const modal = document.getElementById('statusModal');
      const form = document.getElementById('statusForm');
      
      document.getElementById('id_pembayaran').value = id;
      document.getElementById('new_status').value = currentStatus;
      
      modal.classList.remove('hidden');
    }
    
    function closeStatusModal() {
      document.getElementById('statusModal').classList.add('hidden');
    }
    
    // Tutup modal saat klik di luar
    window.onclick = function(event) {
      const modal = document.getElementById('statusModal');
      if (event.target == modal) {
        closeStatusModal();
      }
    }
  </script>
  
  <?php $conn->close(); ?>
</body>
</html>