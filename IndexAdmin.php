<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneak & Treat - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <!-- Admin Navbar -->
  <header class="bg-emerald-800 shadow-lg">
    <div class="mx-auto flex h-16 max-w-screen-xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <div class="flex items-center gap-4">
        <img src="Logo.jpg" alt="Admin Logo" class="h-12 rounded-full">
        <span class="text-white text-xl font-bold">Admin Panel</span>
      </div>
      
      <div class="flex items-center gap-6 text-white">
    <a class="block rounded-md bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600" href="#"> Logout </a>
      </div>
    </div>
  </header>

  <!-- Admin Dashboard Content -->
  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white h-screen shadow-lg p-4">
      <nav>
        <ul class="space-y-2">
          <li>
            <a href="#dashboard" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">
              📊 Dashboard
            </a>
          </li>
          <li>
            <a href="#orders" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">
              📦 Pesanan
            </a>
          </li>
          <li>
            <a href="#customers" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">
              👥 Pelanggan
            </a>
          </li>
          <li>
            <a href="#payments" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">
              💳 Pembayaran
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <!-- Dashboard Stats -->
      <section id="dashboard">
        <h2 class="text-2xl font-bold mb-6 text-emerald-800">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500">Total Pesanan</h3>
            <p class="text-3xl font-bold text-emerald-600">142</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500">Pendapatan Bulan Ini</h3>
            <p class="text-3xl font-bold text-emerald-600">Rp 8.450.000</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500">Pelanggan Aktif</h3>
            <p class="text-3xl font-bold text-emerald-600">58</p>
          </div>
        </div>
      </section>
<?php
session_start();

// Koneksi Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_sepatu";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Handle Tambah Layanan
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
    header("Location: indexAdmin.php");
    exit();
}

// Handle Hapus Layanan
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM Layanan WHERE idLayanan = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Layanan berhasil dihapus!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    header("Location: indexAdmin.php");
    exit();
}

// Ambil Data Layanan
$result = $conn->query("SELECT * FROM Layanan ORDER BY idLayanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto mt-10">
        <!-- Notifikasi -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="p-4 mb-4 text-sm rounded-lg <?= $_SESSION['msg_type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        endif; ?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <section id="services" class="mt-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-emerald-800">Manajemen Layanan</h2>
                    <button onclick="toggleForm()" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                        + Tambah Layanan
                    </button>
                </div>

                <!-- Form Tambah -->
                <div id="serviceForm" class="hidden bg-white p-6 rounded-lg shadow mb-6">
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="w-full p-2 border rounded" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Harga (Rp)</label>
                            <input type="number" name="harga" class="w-full p-2 border rounded" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Durasi Normal</label>
                            <input type="text" name="durasi_normal" class="w-full p-2 border rounded" placeholder="contoh: 3 hari" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Durasi Express</label>
                            <input type="text" name="durasi_express" class="w-full p-2 border rounded" placeholder="contoh: 1-3 hari" required>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                                Simpan
                            </button>
                            <button type="button" onclick="toggleForm()" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Layanan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="bg-white p-6 rounded-lg shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['Nama_Layanan']) ?></h3>
                                    <span class="text-sm text-gray-500">#<?= $row['idLayanan'] ?></span>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-emerald-600 font-bold">
                                        Rp <?= number_format($row['Harga'], 0, ',', '.') ?>
                                    </p>
                                    <div class="text-sm">
                                        <p>Normal: <?= htmlspecialchars($row['Durasi_Pengerjaan']) ?></p>
                                        <p>Express: <?= htmlspecialchars($row['Durasi_Pengerjaan_express']) ?></p>
                                    </div>
                                </div>

                                <div class="flex gap-2 mt-4">
                                    <a href="indexAdmin.php?delete=<?= $row['idLayanan'] ?>"
                                       class="text-red-600 hover:text-red-800"
                                       onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500">Belum ada layanan tersedia</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script>
        function toggleForm() {
            const form = document.getElementById('serviceForm');
            form.classList.toggle('hidden');
        }
    </script>

</body>
</html>

<?php $conn->close(); ?>
        </div>
      </section>
    </main>
  </div>

</body>
</html>