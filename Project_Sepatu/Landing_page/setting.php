<?php
session_start();
require_once 'db_config.php';

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginAwal/login.php");
    exit();
}

// Inisialisasi variabel
$error = '';
$success = '';

// Ambil data customer dari database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT a.idAkun, c.Nama, c.IdCustomer, c.Tanggal_Daftar, c.Alamat, c.No_Telepon 
                       FROM akun a 
                       JOIN customer c ON a.Customer_IdCustomer = c.IdCustomer 
                       WHERE a.idAkun = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

// Jika data tidak ditemukan
if (!$user_data) {
    $error = "Data pengguna tidak ditemukan!";
    header("Location: ../LoginAwal/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan sanitasi input
    $nama = htmlspecialchars(trim($_POST["Nama"]));
    $alamat = htmlspecialchars(trim($_POST["Alamat"]));
    $no_telepon = htmlspecialchars(trim($_POST["No_Telepon"]));

    // Validasi input
    if (empty($nama) || empty($alamat) || empty($no_telepon)) {
        $error = "Semua field wajib diisi!";
    } elseif (!preg_match("/^[0-9]{10,13}$/", $no_telepon)) {
        $error = "Nomor telepon harus 10-13 digit angka";
    } else {
        // Update data ke database
        try {
            // Mulai transaction
            $conn->begin_transaction();
            
            // Update data customer
            $stmt = $conn->prepare("UPDATE customer SET Nama = ?, Alamat = ?, No_Telepon = ? WHERE IdCustomer = ?");
            $stmt->bind_param("ssss", $nama, $alamat, $no_telepon, $user_data['IdCustomer']);
            
            if ($stmt->execute()) {
                $success = "Data akun berhasil diperbarui!";
                // Perbarui data yang ditampilkan
                $user_data["Nama"] = $nama;
                $user_data["Alamat"] = $alamat;
                $user_data["No_Telepon"] = $no_telepon;
                
                // Commit transaction
                $conn->commit();
            } else {
                $error = "Gagal memperbarui data: " . $stmt->error;
                // Rollback transaction
                $conn->rollback();
            }
            
            $stmt->close();
        } catch (Exception $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
            $conn->rollback();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Akun | Sneak & Treat</title>
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
    .success-message {
      color: #10b981;
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }
    .profile-icon {
      background: linear-gradient(135deg, #059669, #047857);
    }
    .btn-primary {
      background: linear-gradient(to right, #fbbf24, #f59e0b);
    }
    .btn-primary:hover {
      background: linear-gradient(to right, #f59e0b, #e6900a);
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-emerald-800 text-white font-sans">

  <!-- Navbar Section -->
  <header class="bg-emerald-800 sticky top-0 z-50 shadow-md">
    <div class="mx-auto flex h-16 max-w-screen-xl items-center gap-8 px-4 sm:px-6 lg:px-8">
      <a class="block text-teal-600 dark:text-teal-300" href="../Landing_page/index.php">
        <span class="sr-only">Home</span>
        <img src="logo.jpg" alt="Logo" class="h-12 rounded-full"> <!-- Perbaikan: menghapus viewBox dan fill yang tidak perlu -->
      </a>

      <div class="flex flex-1 items-center justify-end md:justify-between">
        <nav aria-label="Global" class="hidden md:block">
          <ul class="flex items-center gap-6 text-sm">
            <li> <a class="text-white transition hover:text-yellow-400" href="../Main/pemesanan.php">Pemesanan</a> </li>
          </ul>
        </nav>

        <div class="flex items-center gap-4">
          <div class="sm:flex sm:gap-4">
            <a class="hidden rounded-md bg-white px-5 py-2.5 text-sm font-medium text-black transition hover:bg-black hover:text-white sm:block" 
               href="../LoginAwal/logout.php">Logout</a>
          </div>

          <button
            class="block rounded-sm bg-gray-100 p-2.5 text-gray-600 transition hover:text-gray-600/75 md:hidden dark:bg-gray-800 dark:text-white dark:hover:text-white/75"
          >
            <span class="sr-only">Toggle menu</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="size-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-yellow-300 mb-4">
        <i class="fas fa-user-edit mr-2"></i>Edit Identitas Akun
      </h1>
      <p class="text-gray-300 max-w-2xl mx-auto">
        Perbarui informasi akun Anda di bawah ini. Pastikan data yang Anda berikan valid dan dapat dihubungi.
      </p>
      
      <?php if (!empty($error)): ?>
        <div class="mt-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 max-w-md mx-auto rounded animate-pulse">
          <p><i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?></p>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($success)): ?>
        <div class="mt-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 max-w-md mx-auto rounded animate-pulse">
          <p><i class="fas fa-check-circle mr-2"></i><?php echo $success; ?></p>
        </div>
      <?php endif; ?>
    </div>
    
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition hover:scale-[1.01]">
      <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 py-6 px-8">
        <h2 class="text-2xl font-bold text-white">
          <i class="fas fa-user-circle mr-2"></i>Informasi Akun
        </h2>
      </div>
      
      <div class="p-8 text-gray-800">
        <!-- Info Akun -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 pb-8 border-b border-gray-200">
          <div class="profile-icon w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user text-white text-4xl"></i>
          </div>
          
          <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-emerald-600 font-medium">Nama Lengkap</p>
              <p class="text-lg font-semibold"><?php echo htmlspecialchars($user_data['Nama']); ?></p>
            </div>
            <div>
              <p class="text-sm text-emerald-600 font-medium">ID Pelanggan</p>
              <p class="text-lg font-semibold"><?php echo htmlspecialchars($user_data['IdCustomer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-emerald-600 font-medium">Tanggal Daftar</p>
              <p class="text-lg font-semibold"><?php echo date('d M Y', strtotime($user_data['Tanggal_Daftar'])); ?></p>
            </div>
            <div>
              <p class="text-sm text-emerald-600 font-medium">No. Telepon</p>
              <p class="text-lg font-semibold"><?php echo htmlspecialchars($user_data['No_Telepon']); ?></p>
            </div>
          </div>
        </div>
        
        <!-- Form Edit -->
        <form action="" method="POST">
          <!-- Nama Lengkap -->
          <div class="mb-8">
            <label class="block text-lg font-medium mb-2">
              <i class="fas fa-user text-emerald-600 mr-2"></i>Nama Lengkap
            </label>
            <div class="relative">
              <input 
                type="text" 
                name="Nama" 
                required
                class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                placeholder="Masukkan nama lengkap Anda"
                value="<?php echo htmlspecialchars($user_data['Nama']); ?>"
              >
            </div>
          </div>
          
          <!-- Alamat -->
          <div class="mb-8">
            <label class="block text-lg font-medium mb-2">
              <i class="fas fa-home text-emerald-600 mr-2"></i>Alamat
            </label>
            <div class="relative">
              <textarea 
                name="Alamat" 
                required
                class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                placeholder="Masukkan alamat lengkap Anda"
                rows="3"
              ><?php echo htmlspecialchars($user_data['Alamat']); ?></textarea>
            </div>
          </div>
          
          <!-- No Telepon -->
          <div class="mb-8">
            <label class="block text-lg font-medium mb-2">
              <i class="fas fa-phone text-emerald-600 mr-2"></i>No. Telepon
            </label>
            <div class="relative">
              <input 
                type="text" 
                name="No_Telepon"
                required
                class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                placeholder="Contoh: 081234567890 atau +6281234567890"
                value="<?php echo htmlspecialchars($user_data['No_Telepon']); ?>"
              >
              <p class="text-sm text-gray-500 mt-1">Pastikan nomor aktif dan dapat dihubungi</p>
            </div>
          </div>
              <a href="index.php" 
                 class="px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
              </a>
              <button type="submit" class="btn-primary px-8 py-3 text-black font-bold rounded-lg shadow-lg transition-all">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Informasi Tambahan -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-emerald-700 rounded-xl p-6 text-center transform transition hover:scale-105">
        <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-shield-alt text-2xl text-yellow-300"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Keamanan Akun</h3>
        <p class="text-gray-200">Data akun Anda dilindungi dengan enkripsi dan tidak akan dibagikan ke pihak lain.</p>
      </div>
      
      <div class="bg-emerald-700 rounded-xl p-6 text-center transform transition hover:scale-105">
        <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-history text-2xl text-yellow-300"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Riwayat Akun</h3>
        <p class="text-gray-200">Akun Anda dibuat pada <?php echo date('d M Y', strtotime($user_data['Tanggal_Daftar'])); ?>.</p>
      </div>
      
      <div class="bg-emerald-700 rounded-xl p-6 text-center transform transition hover:scale-105">
        <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-headset text-2xl text-yellow-300"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Bantuan</h3>
        <p class="text-gray-200">Butuh bantuan? Hubungi kami melalui WhatsApp di 081234567890.</p>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <footer class="bg-emerald-800 text-white py-10 mt-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <h3 class="text-lg font-bold mb-4 text-yellow-300">Sneak & Treat</h3>
          <p class="text-gray-300">Layanan cuci sepatu profesional dengan hasil terbaik dan harga terjangkau.</p>
        </div>
        
        <div>
          <h3 class="text-lg font-bold mb-4">Layanan</h3>
          <ul class="space-y-2 text-gray-300">
            <li><a href="#" class="hover:text-yellow-300">Cuci Reguler</a></li>
            <li><a href="#" class="hover:text-yellow-300">Deep Clean</a></li>
            <li><a href="#" class="hover:text-yellow-300">Unyellowing</a></li>
            <li><a href="#" class="hover:text-yellow-300">Repaint</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-lg font-bold mb-4">Akun</h3>
          <ul class="space-y-2 text-gray-300">
            <li><a href="#" class="hover:text-yellow-300">Profil Saya</a></li>
            <li><a href="#" class="hover:text-yellow-300">Riwayat Pesanan</a></li>
            <li><a href="#" class="hover:text-yellow-300">Pengaturan</a></li>
            <li><a href="../LoginAwal/logout.php" class="hover:text-yellow-300">Keluar</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-lg font-bold mb-4">Kontak</h3>
          <ul class="space-y-2 text-gray-300">
            <li class="flex items-start">
              <i class="fas fa-map-marker-alt mt-1 mr-2 text-yellow-300"></i>
              <span>Jl. Sepatu No. 123, Kota Sepatu</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-phone mt-1 mr-2 text-yellow-300"></i>
              <span>0812-3456-7890</span>
            </li>
            <li class="flex items-start">
              <i class="fab fa-instagram mt-1 mr-2 text-yellow-300"></i>
              <span>@sneakandtreat</span>
            </li>
          </ul>
        </div>
      </div>
      
      <div class="border-t border-emerald-700 mt-8 pt-6 text-center text-gray-400">
        <p>&copy; 2025 Sneak & Treat. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>
</html>
