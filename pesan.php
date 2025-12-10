<?php 
require 'config/db.php'; 
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header('Location: dashboard.php');
    exit;
}

$success = $error = '';

if($_POST){
    $berat  = floatval($_POST['berat'] ?? 0);
    $alamat = trim($_POST['alamat'] ?? '');
    $lat    = $_POST['lat'] ?? null;
    $lng    = $_POST['lng'] ?? null;

    if(empty($alamat)) {
        $error = "Alamat jemput wajib diisi!";
    } elseif(empty($lat) || empty($lng)) {
        $error = "Silakan klik peta untuk memilih lokasi jemput!";
    } elseif($berat <= 0) {
        $error = "Berat cucian harus lebih dari 0 kg!";
    } else {
        $total = $berat * 7000;

        $stmt = $pdo->prepare("INSERT INTO orders 
            (customer_id, berat_kg, total_harga, alamat_jemput, lat_jemput, lng_jemput, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'menunggu')");

        if($stmt->execute([$_SESSION['user']['id'], $berat, $total, $alamat, $lat, $lng])){
            $success = "Pesanan berhasil dibuat! Tunggu driver menjemput.";
        } else {
            $error = "Gagal menyimpan pesanan. Coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Laundry • LaundryGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #map { 
            height: 400px; 
            border-radius: 16px; 
            border: 3px solid #8b5cf6;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            #map { height: 300px; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-2xl fixed top-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <h1 class="text-2xl sm:text-3xl font-bold">LaundryGo</h1>
            <a href="dashboard.php" class="text-sm sm:text-base hover:underline">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-6 max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-4">
                Pesan Laundry Antar Jemput
            </h1>
            <p class="text-lg text-gray-600">Pilih lokasi jemput dan isi detail pesanan</p>
        </div>

        <!-- Alert -->
        <?php if($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg mb-8 text-center font-semibold">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-8 rounded-lg text-center mb-8">
                <h3 class="text-2xl font-bold mb-4">Pesanan Berhasil Dibuat!</h3>
                <p class="text-lg mb-6"><?= htmlspecialchars($success) ?></p>
                <a href="dashboard.php" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-10 rounded-xl transition">
                    Kembali ke Dashboard
                </a>
            </div>
        <?php endif; ?>

        <?php if(!$success): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Peta -->
            <div class="order-2 lg:order-1">
                <div id="map" class="rounded-2xl overflow-hidden"></div>
                <div class="mt-4 text-center">
                    <p class="text-lg font-semibold text-purple-700">
                        Klik peta untuk memilih lokasi jemput
                    </p>
                    <p class="text-sm text-gray-600 mt-1">Pastikan titiknya tepat di lokasi Anda</p>
                </div>
            </div>

            <!-- Form -->
            <div class="order-1 lg:order-2">
                <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10">
                    <form method="POST" class="space-y-8">
                        <input type="hidden" name="lat" id="lat" required>
                        <input type="hidden" name="lng" id="lng" required>

                        <div>
                            <label class="block text-xl font-bold text-gray-800 mb-3">
                                Alamat Jemput Lengkap
                            </label>
                            <textarea 
                                name="alamat" 
                                rows="4" 
                                class="w-full p-5 border-2 border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none text-gray-800 text-lg resize-none" 
                                placeholder="Contoh: Jl. Sudirman No.123, RT 05/RW 03, Kelurahan Senayan, Jakarta Selatan" 
                                required></textarea>
                        </div>

                        <div>
                            <label class="block text-xl font-bold text-gray-800 mb-3">
                                Berat Cucian (kg)
                            </label>
                            <input 
                                type="number" 
                                step="0.1" 
                                min="0.5" 
                                name="berat" 
                                class="w-full p-6 text-center text-4xl font-bold text-purple-600 border-2 border-purple-300 rounded-xl focus:border-purple-600 focus:outline-none" 
                                placeholder="5.0" 
                                required>
                            <p class="text-center mt-3 text-gray-600">
                                Harga: <strong>Rp7.000/kg</strong> • Total akan dihitung otomatis
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold text-2xl py-6 rounded-2xl transition transform hover:scale-105 shadow-xl">
                            PESAN SEKARANG
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        const map = L.map('map').setView([-6.2088, 106.8456], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker(e.latlng).addTo(map)
                .bindPopup('<b>Lokasi Jemput Dipilih!</b><br>Lat: ' + e.latlng.lat.toFixed(6) + '<br>Lng: ' + e.latlng.lng.toFixed(6))
                .openPopup();

            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });

        // Default Jakarta jika belum ada koordinat
        if (!document.getElementById('lat').value) {
            document.getElementById('lat').value = -6.2088;
            document.getElementById('lng').value = 106.8456;
        }
    </script>
</body>
</html>