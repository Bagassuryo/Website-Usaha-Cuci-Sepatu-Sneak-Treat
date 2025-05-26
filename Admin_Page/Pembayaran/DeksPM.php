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
    <title>Data Pembayaran</title>
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
            <li><a href="../Pelanggan/DeksPL.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">👥 Pelanggan</a></li>
            <li><a href="Pembayaran/DeksPM.php" class="flex items-center gap-2 p-2 hover:bg-emerald-100 rounded">💳 Pembayaran</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-4">Daftar Pembayaran</h2>

            <?php
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

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="min-w-full bg-white border rounded-lg overflow-hidden">
                    <thead class="bg-emerald-100">
                        <tr>
                            <th class="px-4 py-2">ID Pembayaran</th>
                            <th class="px-4 py-2">Metode</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">ID Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['IdPembayaran']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['Metode_Pembayaran']) ?></td>
                            <td class="px-4 py-2">Rp <?= number_format($row['Jumlah_Pembayaran'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full 
                                    <?= match($row['Status_Pembayaran']) {
                                        'Lunas' => 'bg-emerald-100 text-emerald-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Gagal' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } ?>">
                                    <?= htmlspecialchars($row['Status_Pembayaran']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2"><?= date('d/m/Y H:i', strtotime($row['Tanggal_Pembayaran'])) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['Pesanan_IdPesanan']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">Tidak ada data pembayaran</p>
            <?php endif; ?>
            
            <?php $conn->close(); ?>
        </main>
    </div>
</body>
</html>