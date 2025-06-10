<?php
session_start();

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginAwal/login.php");
    exit();
}

// Koneksi database
require_once 'db_config.php';

// Inisialisasi variabel error
$error = '';

// Ganti query sebelumnya dengan ini:
$stmt = $conn->prepare("SELECT c.IdCustomer, c.Nama, c.No_Telepon, a.Email_Akun, a.idAkun
                       FROM customer c
                       JOIN akun a ON c.IdCustomer = a.Customer_IdCustomer
                       WHERE a.idAkun = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
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

// Kode untuk menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan sanitasi input
    $jenis_sepatu = htmlspecialchars(trim($_POST["jenis_sepatu"]));
    $layanan = htmlspecialchars(trim($_POST["layanan"]));
    $tanggal = htmlspecialchars(trim($_POST["tanggal"]));
    $catatan = isset($_POST["catatan"]) ? htmlspecialchars(trim($_POST["catatan"])) : '';
    
    // Validasi input
    if (empty($jenis_sepatu) || empty($layanan) || empty($tanggal)) {
        $error = "Semua field wajib diisi!";
    } elseif (strtotime($tanggal) < strtotime('today')) {
        $error = "Tanggal tidak boleh di masa lalu";
    } else {
        // Dapatkan harga dari database berdasarkan layanan yang dipilih
        try {
            // Cari idLayanan berdasarkan nama layanan yang dipilih
            $stmt = $conn->prepare("SELECT idLayanan, Harga FROM layanan WHERE Nama_Layanan = ?");
            $stmt->bind_param("s", $layanan);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                throw new Exception("Layanan tidak ditemukan");
            }
            
            $layanan_data = $result->fetch_assoc();
            $idLayanan = $layanan_data['idLayanan'];
            $total_harga = $layanan_data['Harga'];
            
            // Simpan data ke tabel pesanan
            $stmt = $conn->prepare("INSERT INTO pesanan (
                idLayanan, 
                Customer_IdCustomer, 
                Tanggal_Pesanan, 
                Tanggal_Antar, 
                Status_Pesanan, 
                Total_harga, 
                jenis_sepatu, 
                Catatan_Khusus
                ) VALUES (?, ?, NOW(), ?, 'Menunggu', ?, ?, ?)");
            
           $stmt->bind_param(
                "issdss", 
                $idLayanan,
                $user_data['IdCustomer'], // Ini yang diperbaiki
                $tanggal,
                $total_harga,
                $jenis_sepatu,
                $catatan
            );
            
          $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                $id_pesanan = $conn->insert_id;
                $_SESSION['data_pesanan'] = [
                    'id_pesanan' => $id_pesanan,
                    'nama' => $user_data['Nama'],
                    'no_hp' => $user_data['No_Telepon'],
                    'jenis_sepatu' => $jenis_sepatu,
                    'layanan' => $layanan,
                    'tanggal' => $tanggal,
                    'total_harga' => $total_harga,
                    'catatan' => $catatan
                ];
                
                // Simpan data pesanan di session untuk halaman konfirmasi
                $_SESSION['data_pesanan'] = $data_pesanan;
                
                // Redirect ke halaman konfirmasi
                header("Location: Pemesanan.php");
                exit;
            } else {
                throw new Exception("Gagal menyimpan pesanan");
            }
            
            $stmt->close();
        } catch (Exception $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemesanan | Sneak & Treat</title>
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
  </style>
</head>
<body class="bg-emerald-800 text-white font-sans">

 <header class="bg-emerald-800">
    <div class="mx-auto flex h-16 max-w-screen-xl items-center gap-8 px-4 sm:px-6 lg:px-8">
      <a class="block text-teal-600 dark:text-teal-300" href="../Landing_page/index.php">
        <span class="sr-only">Home</span>
        <img src="logo.jpg" alt="Logo" class="h-12 rounded-full"> <!-- Perbaikan: menghapus viewBox dan fill yang tidak perlu -->
      </a>

      <div class="flex flex-1 items-center justify-end md:justify-between">
        <nav aria-label="Global" class="hidden md:block">
          <ul class="flex items-center gap-6 text-sm">
            <li> <a class="text-white transition hover:text-yellow-400" href="Pelacakan_pesanan.php"> Riwayat Pesanan </a> </li>
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

          <!-- Tombol menu mobile -->
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

  <!-- Form Section -->
  <section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-yellow-300 mb-4">
          <i class="fas fa-shoe-prints mr-2"></i>Form Pemesanan Cuci Sepatu
        </h1>
        <p class="text-gray-300 max-w-2xl mx-auto">
          Halo <strong><?php echo htmlspecialchars($user_data['Nama'] ?? 'Pelanggan'); ?></strong>! 
          Isi form di bawah ini untuk memesan layanan cuci sepatu profesional kami. 
          <?php if(isset($user_data['No_Telepon'])): ?>
            Kami akan menghubungi Anda melalui WhatsApp (<?php echo htmlspecialchars($user_data['No_Telepon']); ?>).
          <?php endif; ?>
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
            <i class="fas fa-info-circle mr-2"></i>Informasi Pelanggan
          </h2>
        </div>
        
        <div class="p-8 text-gray-800">
          <!-- Informasi Customer -->
          <div class="mb-8 bg-gray-100 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-emerald-700">
              <i class="fas fa-user-circle mr-2"></i>Data Diri Anda
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="font-medium">Nama Lengkap:</p>
                <p class="text-gray-700"><?php echo htmlspecialchars($user_data['Nama'] ?? 'Belum diisi'); ?></p>
              </div>
              <div>
                <p class="font-medium">No. HP:</p>
                <p class="text-gray-700"><?php echo htmlspecialchars($user_data['No_Telepon'] ?? 'Belum diisi'); ?></p>
              </div>
              <div>
                <p class="font-medium">Email:</p>
                <p class="text-gray-700">
                  <?php 
                  // Karena email tidak diambil di query awal, kita bisa menampilkan pesan default
                  echo 'Silakan hubungi admin untuk informasi email';
                  ?>
                </p>
              </div>
            </div>
            <p class="mt-4 text-sm text-gray-500">
              Jika data di atas tidak benar, silakan perbarui profil Anda.
            </p>
          </div>
          
          <!-- Form Pemesanan -->
          <form action="pemesanan.php" method="POST">
            <!-- Jenis Sepatu -->
            <div class="mb-8">
              <label class="block text-lg font-medium mb-2">
                <i class="fas fa-shoe-prints text-emerald-600 mr-2"></i>Jenis Sepatu
              </label>
              <div class="relative">
                <select 
                  name="jenis_sepatu" 
                  required
                  class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 appearance-none"
                >
                  <option value="">-- Pilih Jenis Sepatu --</option>
                  <option value="Sneakers" <?php echo (isset($_POST['jenis_sepatu']) && $_POST['jenis_sepatu'] == 'Sneakers') ? 'selected' : ''; ?>>Sneakers</option>
                  <option value="Canvas" <?php echo (isset($_POST['jenis_sepatu']) && $_POST['jenis_sepatu'] == 'Canvas') ? 'selected' : ''; ?>>Canvas</option>
                  <option value="Kulit" <?php echo (isset($_POST['jenis_sepatu']) && $_POST['jenis_sepatu'] == 'Kulit') ? 'selected' : ''; ?>>Kulit</option>
                  <option value="Boots" <?php echo (isset($_POST['jenis_sepatu']) && $_POST['jenis_sepatu'] == 'Boots') ? 'selected' : ''; ?>>Boots</option>
                  <option value="Sepatu Anak" <?php echo (isset($_POST['jenis_sepatu']) && $_POST['jenis_sepatu'] == 'Sepatu Anak') ? 'selected' : ''; ?>>Sepatu Anak</option>
                  <option value="Sepatu Olahraga" <?php echo (isset($_POST['jenis_sepatu']) && $_POST['jenis_sepatu'] == 'Sepatu Olahraga') ? 'selected' : ''; ?>>Sepatu Olahraga</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <i class="fas fa-chevron-down"></i>
                </div>
              </div>
            </div>
            
            <!-- Layanan -->
            <div class="mb-8">
              <label class="block text-lg font-medium mb-2">
                <i class="fas fa-concierge-bell text-emerald-600 mr-2"></i>Layanan Cuci
              </label>
              <div class="relative">
                <select 
                  name="layanan" 
                  required
                  class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 appearance-none"
                >
                  <option value="">-- Pilih Layanan --</option>
                  <?php
                  // Ambil data layanan dari database
                  $layanan_result = $conn->query("SELECT * FROM layanan");
                  if ($layanan_result) {
                      while ($row = $layanan_result->fetch_assoc()):
                  ?>
                    <option value="<?php echo htmlspecialchars($row['Nama_Layanan']); ?>" 
                      <?php echo (isset($_POST['layanan']) && $_POST['layanan'] == $row['Nama_Layanan']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($row['Nama_Layanan']); ?> - Rp <?php echo number_format($row['Harga'], 0, ',', '.'); ?>
                    </option>
                  <?php 
                      endwhile;
                  } else {
                      echo '<option value="">Tidak ada layanan tersedia</option>';
                  }
                  ?>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <i class="fas fa-chevron-down"></i>
                </div>
              </div>
            </div>
            
            <!-- Tanggal Antar -->
            <div class="mb-8">
              <label class="block text-lg font-medium mb-2">
                <i class="fas fa-calendar-check text-emerald-600 mr-2"></i>Tanggal Antar
              </label>
              <div class="relative">
                <input 
                  type="date" 
                  name="tanggal" 
                  required
                  class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                  min="<?php echo date('Y-m-d'); ?>"
                  value="<?php echo isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : ''; ?>"
                >
                <p class="text-sm text-gray-500 mt-1">Pilih tanggal pengantaran sepatu</p>
              </div>
            </div>
            
            <!-- Catatan Khusus -->
            <div class="mb-8">
              <label class="block text-lg font-medium mb-2">
                <i class="fas fa-edit text-emerald-600 mr-2"></i>Catatan Khusus (Opsional)
              </label>
              <textarea 
                name="catatan"
                class="w-full p-4 border border-gray-300 rounded-lg form-input focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                placeholder="Masukkan catatan khusus untuk pesanan Anda"
                rows="3"
              ><?php echo isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : ''; ?></textarea>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-200">
              <div class="text-sm text-gray-500">
                <p>Dengan mengirim formulir ini, Anda menyetujui syarat dan ketentuan kami.</p>
              </div>
              <button 
                type="submit"
                class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-black font-bold rounded-lg shadow-lg hover:from-yellow-500 hover:to-yellow-600 transition-all transform hover:scale-105"
              >
                <i class="fas fa-paper-plane mr-2"></i>Kirim Pesanan
              </button>
            </div>
          </form>
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