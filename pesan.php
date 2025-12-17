<?php 
require 'config/db.php'; 

// Cek Login
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header('Location: dashboard.php');
    exit;
}

$success = $error = '';

if($_POST){
    $berat   = floatval($_POST['berat'] ?? 0);
    $alamat  = trim($_POST['alamat'] ?? '');
    $layanan = $_POST['layanan'] ?? 'Kiloan Hemat'; 
    $lat     = $_POST['lat'] ?? null;
    $lng     = $_POST['lng'] ?? null;

    $harga_per_kg = 7000; 
    if($layanan == 'Express Premium') {
        $harga_per_kg = 12000;
    } elseif($layanan == 'Satuan') {
        $harga_per_kg = 0; 
    }

    if(empty($alamat)) {
        $error = "Alamat jemput wajib diisi!";
    } elseif(empty($lat) || empty($lng)) {
        $error = "Silakan klik peta untuk memilih lokasi jemput!";
    } elseif($berat <= 0) {
        $error = "Berat cucian harus lebih dari 0 kg!";
    } else {
        $total = $berat * $harga_per_kg;

        $stmt = $pdo->prepare("INSERT INTO orders 
            (customer_id, berat_kg, layanan, total_harga, alamat_jemput, lat_jemput, lng_jemput, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'menunggu')");

        if($stmt->execute([$_SESSION['user']['id'], $berat, $layanan, $total, $alamat, $lat, $lng])){
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
        #map { height: 400px; border-radius: 1rem; z-index: 1; }
        nav { z-index: 50 !important; }
        .service-radio:checked + div {
            border-color: #2563eb; 
            background-color: #eff6ff;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 relative overflow-x-hidden min-h-screen">

    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-cyan-100 rounded-full blur-3xl opacity-50 -z-10"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed top-0 w-full shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition">
                    <i class="fas fa-soap"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-800 hidden sm:block">
                    Laundry<span class="text-blue-500">Go</span>
                </h1>
            </a>
            
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="text-slate-500 hover:text-blue-600 font-medium transition flex items-center gap-2 text-sm">
                    <i class="fas fa-arrow-left"></i> Kembali Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="pt-28 pb-12 px-6 max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2">Pesan Laundry Baru</h1>
            <p class="text-slate-500">Pilih paket layanan dan tentukan lokasi jemput.</p>
        </div>

        <?php if($error): ?>
            <div class="max-w-4xl mx-auto bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3 animate-bounce">
                <i class="fas fa-exclamation-circle text-xl"></i><span class="font-medium"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="max-w-2xl mx-auto bg-white border border-green-200 shadow-xl rounded-3xl p-10 text-center mb-8">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6"><i class="fas fa-check"></i></div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Pesanan Diterima!</h3>
                <p class="text-slate-500 mb-8"><?= htmlspecialchars($success) ?></p>
                <a href="dashboard.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-blue-200">Lihat Status Pesanan</a>
            </div>
        <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div class="bg-white p-4 rounded-3xl shadow-xl border border-slate-100">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2"><i class="fas fa-map-marker-alt text-red-500"></i> Lokasi Jemput</h3>
                    <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded">Wajib Diklik / Cari Otomatis</span>
                </div>
                <div id="map" class="shadow-inner border border-slate-200"></div>
                <p class="text-center text-xs text-slate-400 mt-4"><i class="fas fa-sync"></i> Mengetik alamat akan otomatis menggeser peta, dan sebaliknya.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="lat" id="lat" required>
                    <input type="hidden" name="lng" id="lng" required>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-3 ml-1">Pilih Layanan</label>
                        <div class="space-y-3">
                            <label class="cursor-pointer block relative">
                                <input type="radio" name="layanan" value="Kiloan Hemat" class="service-radio peer sr-only" checked onchange="updateTotal()">
                                <div class="p-4 rounded-xl border border-slate-200 hover:border-blue-300 transition flex justify-between items-center bg-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center"><i class="fas fa-tshirt"></i></div>
                                        <div>
                                            <p class="font-bold text-slate-800">Kiloan Hemat</p>
                                            <p class="text-xs text-slate-500">2-3 Hari • Cuci Setrika</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-blue-600">Rp7.000<span class="text-xs text-slate-400 font-normal">/kg</span></p>
                                </div>
                            </label>

                            <label class="cursor-pointer block relative">
                                <input type="radio" name="layanan" value="Express Premium" class="service-radio peer sr-only" onchange="updateTotal()">
                                <div class="p-4 rounded-xl border border-slate-200 hover:border-blue-300 transition flex justify-between items-center bg-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center"><i class="fas fa-bolt"></i></div>
                                        <div>
                                            <p class="font-bold text-slate-800">Express Premium</p>
                                            <p class="text-xs text-slate-500">6 Jam Selesai • Prioritas</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-blue-600">Rp12.000<span class="text-xs text-slate-400 font-normal">/kg</span></p>
                                </div>
                            </label>

                            <label class="cursor-pointer block relative">
                                <input type="radio" name="layanan" value="Satuan" class="service-radio peer sr-only" onchange="updateTotal()">
                                <div class="p-4 rounded-xl border border-slate-200 hover:border-blue-300 transition flex justify-between items-center bg-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center"><i class="fas fa-layer-group"></i></div>
                                        <div>
                                            <p class="font-bold text-slate-800">Satuan / Dry Clean</p>
                                            <p class="text-xs text-slate-500">Jas, Sepatu, Bedcover</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-blue-600 text-sm">Menyesuaikan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2 ml-1">Alamat Lengkap (Ketik untuk cari di peta)</label>
                        <textarea id="alamatInput" name="alamat" rows="3" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 outline-none transition resize-none placeholder-slate-400" placeholder="Ketik lokasi (misal: Monas, Jakarta)..." required></textarea>
                        <p id="loadingText" class="text-xs text-blue-500 mt-1 hidden"><i class="fas fa-spinner fa-spin"></i> Mencari lokasi...</p>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2 ml-1">Perkiraan Berat (Kg)</label>
                        <div class="relative">
                            <input type="number" step="0.1" min="1" id="beratInput" name="berat" class="w-full p-4 pl-4 pr-16 text-2xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 outline-none transition" placeholder="0" required oninput="updateTotal()">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">KG</span>
                        </div>
                        <p class="text-right text-sm text-slate-500 mt-2">Estimasi Total: <span id="totalDisplay" class="text-blue-600 font-bold text-lg">Rp0</span></p>
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
        // --- 1. SETUP MAP ---
        const map = L.map('map').setView([-6.2088, 106.8456], 13); // Default Jakarta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        
        let marker;
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        const alamatInput = document.getElementById('alamatInput');
        const loadingText = document.getElementById('loadingText');

        function setLocation(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Jemput').openPopup();
            latInput.value = lat;
            lngInput.value = lng;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15);
            });
        }

        // --- 2. LOGIKA KLIK MAP (MENGGUNAKAN API BigDataCloud) ---
        // Alasan: Lebih stabil untuk free usage dibanding Nominatim yang sering error 429
        map.on('click', async function(e) {
            const { lat, lng } = e.latlng;
            setLocation(lat, lng);
            
            alamatInput.setAttribute('placeholder', 'Sedang mengambil alamat...');
            
            try {
                // Gunakan BigDataCloud (Gratis & Cepat)
                const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=id`);
                const data = await response.json();
                
                // Susun format alamat dari data BigDataCloud
                const parts = [];
                if(data.locality) parts.push(data.locality);
                if(data.city) parts.push(data.city);
                if(data.principalSubdivision) parts.push(data.principalSubdivision);
                if(data.countryName) parts.push(data.countryName);

                if(parts.length > 0) {
                    alamatInput.value = parts.join(", ");
                } else {
                    alamatInput.value = "Alamat tidak terdeteksi detail, silakan lengkapi.";
                }
            } catch (error) {
                console.error("Gagal mengambil alamat:", error);
                alamatInput.value = ""; 
                alert("Gagal koneksi ke server maps. Silakan ketik alamat manual.");
            }
        });

        // --- 3. LOGIKA KETIK ALAMAT (Masih pakai Nominatim tapi di-debounce) ---
        let typingTimer;
        alamatInput.addEventListener('input', function() {
            const query = alamatInput.value;
            clearTimeout(typingTimer);
            
            if(query.length > 3) {
                loadingText.classList.remove('hidden');
                
                typingTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
                        const data = await response.json();
                        
                        if(data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;
                            map.setView([lat, lon], 16);
                            setLocation(lat, lon);
                        }
                    } catch (error) {
                        console.error("Lokasi tidak ditemukan via search");
                    } finally {
                        loadingText.classList.add('hidden');
                    }
                }, 1500); // Delay 1.5 detik agar tidak spam request
            }
        });

        // --- 4. LOGIKA HITUNG HARGA ---
        function updateTotal() {
            const berat = parseFloat(document.getElementById('beratInput').value) || 0;
            const radios = document.getElementsByName('layanan');
            let hargaPerKg = 7000;
            let layanan = '';

            for (const radio of radios) {
                if (radio.checked) {
                    layanan = radio.value;
                    break;
                }
            }

            if (layanan === 'Express Premium') hargaPerKg = 12000;
            if (layanan === 'Satuan') hargaPerKg = 0; 

            const total = berat * hargaPerKg;
            
            if(layanan === 'Satuan') {
                document.getElementById('totalDisplay').innerText = "Menunggu Admin";
            } else {
                document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
            }
        }
    </script>
</body>
</html>