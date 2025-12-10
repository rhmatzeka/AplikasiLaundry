<?php 
require 'config/db.php'; 

// Cek Login
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* FIX BUG MAP: Navbar harus z-50, Map z-0 */
        #map { 
            height: 400px; 
            border-radius: 1rem; 
            z-index: 1; /* Z-Index rendah */
        }
        
        /* Navbar Z-Index Tinggi agar selalu di atas */
        nav { z-index: 50 !important; }

        @media (max-width: 768px) {
            #map { height: 300px; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 relative overflow-x-hidden min-h-screen">

    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-cyan-100 rounded-full blur-3xl opacity-50 -z-10"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed top-0 w-full shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="dashboard.php" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition">
                    <i class="fas fa-soap"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-800 hidden sm:block">
                    Laundry<span class="text-blue-500">Go</span>
                </h1>
            </a>
            
            <a href="dashboard.php" class="text-slate-500 hover:text-blue-600 font-medium transition flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="pt-28 pb-12 px-6 max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2">
                Pesan Laundry Baru
            </h1>
            <p class="text-slate-500">Isi form di bawah, driver kami siap menjemput.</p>
        </div>

        <?php if($error): ?>
            <div class="max-w-4xl mx-auto bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3 animate-bounce">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <span class="font-medium"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="max-w-2xl mx-auto bg-white border border-green-200 shadow-xl rounded-3xl p-10 text-center mb-8">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Pesanan Diterima!</h3>
                <p class="text-slate-500 mb-8"><?= htmlspecialchars($success) ?></p>
                <a href="dashboard.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-blue-200">
                    Lihat Status Pesanan
                </a>
            </div>
        <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <div class="bg-white p-4 rounded-3xl shadow-xl border border-slate-100">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-500"></i> Lokasi Jemput
                    </h3>
                    <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded">Wajib Diklik</span>
                </div>
                
                <div id="map" class="shadow-inner border border-slate-200"></div>
                
                <p class="text-center text-sm text-slate-500 mt-4">
                    <i class="fas fa-info-circle text-blue-500"></i> Klik pada peta untuk menandai lokasi rumah Anda.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="lat" id="lat" required>
                    <input type="hidden" name="lng" id="lng" required>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2 ml-1">Alamat Lengkap</label>
                        <textarea 
                            name="alamat" 
                            rows="3" 
                            class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition text-slate-700 placeholder-slate-400 resize-none" 
                            placeholder="Jalan, Nomor Rumah, RT/RW, Patokan..." 
                            required></textarea>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2 ml-1">Perkiraan Berat (Kg)</label>
                        <div class="relative">
                            <input 
                                type="number" 
                                step="0.1" 
                                min="1" 
                                name="berat" 
                                class="w-full p-4 pl-4 pr-16 text-2xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition" 
                                placeholder="0" 
                                required>
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">KG</span>
                        </div>
                        <p class="text-right text-xs text-slate-400 mt-2">Harga: <span class="text-blue-600 font-bold">Rp7.000 /kg</span></p>
                    </div>

                    <hr class="border-slate-100 my-4">

                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Pesanan
                    </button>
                </form>
            </div>
        </div>

        <?php endif; ?>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta (Start di Monas Jakarta)
        const map = L.map('map').setView([-6.175392, 106.827153], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;

        // Fungsi saat peta diklik
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker(e.latlng).addTo(map)
                .bindPopup('<div class="text-center font-bold text-blue-600">Lokasi Jemput<br><span class="text-xs text-slate-500">Titik Terpilih</span></div>')
                .openPopup();

            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });

        // Cek Geolocation Browser User agar otomatis ke lokasi dia
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15);
            });
        }
    </script>
</body>
</html>