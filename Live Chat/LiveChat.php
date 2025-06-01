<?php
$Layanan = [
   ['nama' => 'Deep Clean', 'harga' => 'Rp 30.000', 'deskripsi' => 'Membersihkan sepatu secara menyeluruh hingga ke bagian dalam.'],
    ['nama' => 'Reguler', 'harga' => 'Rp 15.000 - 30.000', 'deskripsi' => 'Pencucian standar untuk sepatu sehari-hari.'],
    ['nama' => 'Repaint', 'harga' => 'Rp 65.000', 'deskripsi' => 'Pengecatan ulang sepatu agar tampak seperti baru.'],
    ['nama' => 'Unyellowing', 'harga' => 'Rp 40.000', 'deskripsi' => 'Menghilangkan warna kuning pada sol sepatu.'],
    ['nama' => 'Reparasi Sepatu', 'harga' => 'Rp 65.000', 'deskripsi' => 'Perbaikan kerusakan ringan pada sepatu Anda.'],
    ['nama' => 'Kid Shoes', 'harga' => 'Rp 20.000', 'deskripsi' => 'Layanan cuci khusus sepatu anak-anak.'],
    ['nama' => 'Express Clean', 'harga' => 'Rp 50.000', 'deskripsi' => 'Layanan cuci kilat, selesai dalam waktu singkat.']
];

$jam_operasional = [
    'Setiap Hari' => '12:00 - 22:00 WIB',
];

$nomor_wa = '6281357423730';
$nama_usaha = 'Sneak & Treat';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Widget</title>
    <link rel="stylesheet" href="chat.css">
    <script src="chat.js"></script>
</head>

<body>
    <div class="chat-widget">
        <div class="chat-button" onclick="toggleChat()">
            <div class="notification-dot"></div>
            <svg class="chat-icon" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.63z" />
            </svg>
        </div>
        <div class="chat-panel" id="chatPanel">
            <div class="chat-header">
                <button class="close-btn" onclick="toggleChat()">&times;</button>
                <h3><?php echo $nama_usaha; ?></h3>
                <p>Informasi Tentang Kami</p>
            </div>

            <div class="menu-tabs">
                <button class="tab-btn active" onclick="showTab('layanan')">Layanan</button>
                <button class="tab-btn" onclick="showTab('jam')">Jam Operasional</button>
                <button class="tab-btn" onclick="showTab('kontak')">Kontak Cepat</button>
            </div>

            <div class="chat-content">
                <div id="layanan" class="tab-content active">
                    <div class="status-indicator">
                        <div class="status-dot" id="statusDot1"></div>
                        <div class="status-text" id="statusText1">Memuat status...</div>
                    </div>

                    <?php foreach ($Layanan as $layanan): ?>
                        <div class="service-item">
                            <div class="service-name"><?php echo $layanan['nama']; ?></div>
                            <div class="service-price"><?php echo $layanan['harga']; ?></div>
                            <div class="service-desc"><?php echo $layanan['deskripsi']; ?></div>
                        </div>
                    <?php endforeach; ?>

                    <button class="whatsapp-btn" onclick="openWhatsApp('Halo, saya tertarik dengan layanan Anda. Bisakah Anda memberikan informasi lebih lanjut?')">
                        <svg class="whatsapp-icon" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.63z" />
                        </svg>
                        Hubungi via WhatsApp
                    </button>
                </div>

                <div id="jam" class="tab-content">
                    <div class="status-indicator">
                        <div class="status-dot" id="statusDot2"></div>
                        <div class="status-text" id="statusText2">Memuat status...</div>
                    </div>

                    <?php foreach ($jam_operasional as $day => $time): ?>
                        <div class="hours-item">
                            <span class="day"><?php echo $day; ?></span>
                            <span class="time"><?php echo $time; ?></span>
                        </div>
                    <?php endforeach; ?>

                    <button class="whatsapp-btn" onclick="openWhatsApp('Halo, saya ingin mengetahui lebih lanjut tentang jam operasional Anda.')">
                        <svg class="whatsapp-icon" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.63z" />
                        </svg>
                        Tanya Jam Kerja
                    </button>
                </div>

                <div id="kontak" class="tab-content">
                    <div class="quick-messages">
                        <button class="quick-msg" onclick="openWhatsApp('Halo, saya ingin menanyakan jenis layanan cuci sepatu.')">
                            👟 Tanya Layanan
                        </button>
                        <button class="quick-msg" onclick="openWhatsApp('Berapa harga untuk mencuci sepatu saya?')">
                            💰 Tanya Harga
                        </button>
                        <button class="quick-msg" onclick="openWhatsApp('Apakah bisa antar jemput sepatu?')">
                            🚚 Tanya Antar Jemput
                        </button>
                        <button class="quick-msg" onclick="openWhatsApp('Saya ingin membuat janji untuk antar sepatu.')">
                            📅 Buat Janji
                        </button>
                    </div>

                    <button class="whatsapp-btn" onclick="openWhatsApp('Halo, saya memiliki pertanyaan lebih lanjut.')">
                        <svg class="whatsapp-icon" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.30z" />
                        </svg>
                        Chat Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>