<?php
session_start();
include('conn.php');

// Generate CSRF Token (untuk form jika diperlukan)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white h-screen shadow-lg p-4">
            <nav>
                <ul class="space-y-2">
                    <li><a href="../IndexAdmin.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">📊 Dashboard</a></li>
          <li><a href="../Pesanan/DeksP.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">📦 Pesanan</a></li>
          <li><a href="Pelanggan/DeksPL.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">👥 Pelanggan</a></li>
          <li><a href="../Pembayaran/DeksPM.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">💳 Pembayaran</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-4">Daftar Pelanggan</h2>
            
            <?php
            $sql = "SELECT 
                        IdCustomer, 
                        Nama, 
                        Alamat, 
                        Kode_Unik, 
                        No_Telepon, 
                        Tanggal_Daftar 
                    FROM customer 
                    ORDER BY Tanggal_Daftar DESC";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
            ?>
                <table class="min-w-full bg-white border rounded-lg overflow-hidden">
                    <thead class="bg-emerald-100">
                        <tr>
                            <th class="px-4 py-2">ID Pelanggan</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Alamat</th>
                            <th class="px-4 py-2">Kode Unik</th>
                            <th class="px-4 py-2">No. Telepon</th>
                            <th class="px-4 py-2">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['IdCustomer']) ?></td>
                            <td class="px-4 py-2 font-medium"><?= htmlspecialchars($row['Nama']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['Alamat']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['Kode_Unik']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['No_Telepon']) ?></td>
                            <td class="px-4 py-2"><?= date('d/m/Y H:i', strtotime($row['Tanggal_Daftar'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo '<p class="text-gray-500">Tidak ada data pelanggan</p>';
            }
            
            $conn->close();
            ?>
        </main>
    </div>
</body>
</html>