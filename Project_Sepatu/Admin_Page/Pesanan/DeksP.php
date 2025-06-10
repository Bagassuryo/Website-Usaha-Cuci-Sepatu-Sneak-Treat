<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneak & Treat - Manajemen Pesanan</title>
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
    
    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
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
    
    .status-menunggu {
      background-color: #fef3c7;
      color: #d97706;
    }
    
    .status-diproses {
      background-color: #dbeafe;
      color: #2563eb;
    }
    
    .status-selesai {
      background-color: #dcfce7;
      color: #16a34a;
    }
    
    .status-dibatalkan {
      background-color: #fee2e2;
      color: #dc2626;
    }
  </style>
</head>
<body class="bg-gray-100">

<?php
session_start();
include ('conn.php'); 

// Generate CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Tampilkan notifikasi
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

$sql = "SELECT 
            idPesanan, 
            Tanggal_Pesanan, 
            Status_Pesanan, 
            Total_Harga, 
            Catatan_Khusus, 
            Customer_idCustomer 
        FROM pesanan 
        ORDER BY Tanggal_Pesanan DESC";

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
            <a href="DeksP.php" class="nav-item active flex items-center gap-3 p-3 rounded-lg">
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
            <a href="../Pembayaran/DeksPM.php" class="nav-item flex items-center gap-3 p-3 rounded-lg">
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
            <h1 class="text-xl font-bold text-gray-800">Manajemen Pesanan</h1>
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
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Daftar Pesanan</h2>
            <p class="text-gray-600">Kelola semua pesanan pelanggan Sneak&Treat</p>
          </div>
        </div>

        <!-- Notifications -->
        <?php if (isset($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
              <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
          <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
              <i class="fas fa-check-circle mr-3 text-lg"></i>
              <span><?= htmlspecialchars($success) ?></span>
            </div>
          </div>
        <?php endif; ?>

        <!-- Order Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-container">
              <table class="min-w-full">
                <thead>
                  <tr class="table-header">
                    <th class="px-6 py-3 text-left text-sm font-medium">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Total Harga</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Catatan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">ID Customer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="table-row">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= htmlspecialchars($row['idPesanan']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($row['Tanggal_Pesanan']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <?php 
                          $statusClass = 'status-' . strtolower($row['Status_Pesanan']);
                          $statusText = htmlspecialchars($row['Status_Pesanan']);
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                          <?= $statusText ?>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp <?= number_format($row['Total_Harga'], 0, ',', '.') ?>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                        <?= htmlspecialchars($row['Catatan_Khusus'] ?? '-') ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($row['Customer_idCustomer']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <form method="post" action="../Pesanan/update.php" class="inline">
                          <input type="hidden" name="idPesanan" value="<?= htmlspecialchars($row['idPesanan']) ?>">
                          <div class="flex items-center gap-2">
                            <select name="Status_Pesanan" class="border rounded px-2 py-1 text-sm w-32 focus:ring-primary focus:border-primary">
                              <option value="Menunggu" <?= ($row['Status_Pesanan'] == 'Menunggu') ? 'selected' : '' ?>>Menunggu</option>
                              <option value="Diproses" <?= ($row['Status_Pesanan'] == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                              <option value="Selesai" <?= ($row['Status_Pesanan'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                              <option value="Dibatalkan" <?= ($row['Status_Pesanan'] == 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <button type="submit" class="bg-primary text-white px-3 py-1 rounded hover:bg-secondary text-sm">
                              <i class="fas fa-sync-alt mr-1"></i>Update
                            </button>
                          </div>
                        </form>
                        
                        <form method="post" action="../Pesanan/delete.php" class="inline ml-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                          <input type="hidden" name="idPesanan" value="<?= htmlspecialchars($row['idPesanan']) ?>">
                          <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                            <i class="fas fa-trash mr-1"></i>Hapus
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-12 text-center">
              <i class="fas fa-shopping-bag text-4xl text-gray-400 mb-4"></i>
              <h4 class="text-gray-600 font-medium mb-2">Belum ada pesanan</h4>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>
  <script>
    // Mobile sidebar toggle
    document.querySelector('button[aria-label="Toggle sidebar"]')?.addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('hidden');
      document.querySelector('.sidebar').classList.toggle('md:flex');
    });
  </script>
  
</body>
</html>