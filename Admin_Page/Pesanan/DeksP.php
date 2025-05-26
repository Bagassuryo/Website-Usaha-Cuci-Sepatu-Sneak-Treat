<?php
session_start();
 include ('conn.php'); 

// Generate CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION['success'])) {
    echo '<div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-4">'
        . htmlspecialchars($_SESSION['success'])
        . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">'
        . htmlspecialchars($_SESSION['error'])
        . '</div>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        table {
            table-layout: auto;
        }
    </style>
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white h-screen shadow-lg p-4">
            <nav>
                <ul class="space-y-2">
                    <li><a href="../IndexAdmin.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">📊 Dashboard</a></li>
                    <li><a href="Pesanan/DeksP.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">📦 Pesanan</a></li>
                    <li><a href="../Pelanggan/DeksPL.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">👥 Pelanggan</a></li>
                    <li><a href="../Pembayaran/DeksPM.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">💳 Pembayaran</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-4">Daftar Pesanan</h2>
            
            <?php
            // Tampilkan notifikasi
            if (isset($_SESSION['error'])) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])) : ?>
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php
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

            if ($result && $result->num_rows > 0) 
                echo '<table class="min-w-full bg-white border rounded-lg overflow-hidden">
                        <thead class="bg-emerald-100">
                            <tr>
                                <th class="px-4 py-2">ID Pesanan</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Total Harga</th>
                                <th class="px-4 py-2">Catatan</th>
                                <th class="px-4 py-2">ID Customer</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>';

               while($row = $result->fetch_assoc()): ?>
    <tr class="hover:bg-gray-50">
        <td class="px-4 py-2"><?= htmlspecialchars($row['idPesanan']) ?></td>
        <td class="px-4 py-2"><?= htmlspecialchars($row['Tanggal_Pesanan']) ?></td>
        <td class="px-4 py-2">
            <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800">
                <?= htmlspecialchars($row['Status_Pesanan']) ?>
            </span>
        </td>
        <td class="px-4 py-2">Rp <?= number_format($row['Total_Harga'], 0, ',', '.') ?></td>
        <td class="px-4 py-2"><?= htmlspecialchars($row['Catatan_Khusus'] ?? '-') ?></td>
        <td class="px-4 py-2"><?= htmlspecialchars($row['Customer_idCustomer']) ?></td>
        <td class="px-4 py-2">
            <form method="post" action="../Pesanan/update.php">
                <input type="hidden" name="idPesanan" value="<?= htmlspecialchars($row['idPesanan']) ?>">
                <select name="Status_Pesanan" class="border rounded px-2 py-1 text-sm">
                    <option value="Menunggu" <?= ($row['Status_Pesanan'] == 'Menunggu') ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Diproses" <?= ($row['Status_Pesanan'] == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai" <?= ($row['Status_Pesanan'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                    <option value="Dibatalkan" <?= ($row['Status_Pesanan'] == 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
                <button type="submit" class="bg-emerald-500 text-white px-3 py-1 rounded hover:bg-emerald-600 text-sm mt-1">
                    Update
                </button>
            </form>
            
            <!-- Tombol Hapus -->
            <form method="post" action="../Pesanan/delete.php" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                <input type="hidden" name="idPesanan" value="<?= htmlspecialchars($row['idPesanan']) ?>">
                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm mt-1 ml-1">
                Hapus
            </button>
            </form>
        </td>
    </tr>
<?php endwhile; 
     $conn->close();
            ?>
        </main>
    </div>
</body>
</html>