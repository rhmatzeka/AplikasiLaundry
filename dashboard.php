<?php 
require 'config/db.php'; 
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];

// --- LOGIKA ADMIN ASSIGN DRIVER ---
if($user['role'] == 'admin' && isset($_POST['assign_driver'])) {
    $order_id = $_POST['order_id'];
    $driver_id = $_POST['driver_id'];
    
    // Update order dengan driver yang dipilih dan ubah status jadi 'dikonfirmasi'
    $stmt = $pdo->prepare("UPDATE orders SET driver_id = ?, status = 'dikonfirmasi' WHERE id = ?");
    $stmt->execute([$driver_id, $order_id]);
    echo "<script>alert('Driver berhasil ditugaskan!'); window.location='dashboard.php';</script>";
}

// --- LOGIKA DRIVER UPDATE STATUS (BERTINGKAT) ---
if($user['role'] == 'driver' && isset($_GET['update_status']) && isset($_GET['id'])) {
    $oid = $_GET['id'];
    $next_status = $_GET['update_status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ? AND driver_id = ?");
    $stmt->execute([$next_status, $oid, $user['id']]);
    header("Location: dashboard.php");
    exit;
}

// --- LOGIKA DRIVER UPDATE LOKASI (AJAX Endpoint) ---
// Bagian ini biasanya dipisah file, tapi untuk kemudahan saya taruh logika di sini jika ada parameter khusus
if(isset($_POST['ajax_update_loc']) && $user['role'] == 'driver') {
    $oid = $_POST['order_id'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $stmt = $pdo->prepare("UPDATE orders SET lat_driver = ?, lng_driver = ? WHERE id = ?");
    $stmt->execute([$lat, $lng, $oid]);
    exit; // Stop render HTML
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard • LaundryGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .badge { @apply px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider; }
        /* Warna Status Baru */
        .status-menunggu { @apply bg-gray-200 text-gray-600; }
        .status-dikonfirmasi { @apply bg-yellow-100 text-yellow-600; }
        .status-menjemput_pakaian { @apply bg-orange-100 text-orange-600; }
        .status-menuju_laundry { @apply bg-purple-100 text-purple-600; }
        .status-proses_cuci { @apply bg-blue-100 text-blue-600; }
        .status-mengantar_bersih { @apply bg-indigo-100 text-indigo-600; }
        .status-selesai { @apply bg-green-100 text-green-600; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 relative min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed top-0 w-full z-50 transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">
                    <i class="fas fa-soap"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-800 hidden sm:block">Laundry<span class="text-blue-500">Go</span></h1>
            </a>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-700">Hai, <?= htmlspecialchars($user['nama']) ?></p>
                    <p class="text-xs text-slate-500 capitalize"><?= $user['role'] ?></p>
                </div>
                <a href="logout.php" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl text-sm font-bold transition border border-red-100">Logout</a>
            </div>
        </div>
    </nav>

    <div class="pt-28 pb-12 px-6 max-w-7xl mx-auto">
        
        <?php if($user['role'] == 'customer'): ?>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-800">Layanan Kami</h2>
                <p class="text-slate-500">Pantau live tracking driver saat status berubah.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-10">
                <a href="pesan.php" class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 hover:border-blue-300 transition text-center group">
                    <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 group-hover:scale-110 transition"><i class="fas fa-tshirt"></i></div>
                    <h3 class="font-bold text-xl">Pesan Laundry</h3>
                </a>
                <a href="riwayat.php" class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 hover:border-cyan-300 transition text-center group">
                    <div class="w-20 h-20 bg-cyan-50 text-cyan-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 group-hover:scale-110 transition"><i class="fas fa-history"></i></div>
                    <h3 class="font-bold text-xl">Riwayat & Tracking</h3>
                </a>
            </div>

            <h3 class="font-bold text-xl mb-4">Pesanan Aktif (Live Map)</h3>
            <?php 
            $qTrack = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? AND status NOT IN ('selesai','dibatalkan','menunggu')");
            $qTrack->execute([$user['id']]);
            while($t = $qTrack->fetch()):
            ?>
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-100 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="badge status-<?= $t['status'] ?>"><?= strtoupper(str_replace('_', ' ', $t['status'])) ?></span>
                    <span class="text-xs font-mono">Order #<?= $t['id'] ?></span>
                </div>
                <div id="map_<?= $t['id'] ?>" class="h-64 rounded-xl z-0"></div>
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    (function(){
                        const map = L.map('map_<?= $t['id'] ?>').setView([<?= $t['lat_jemput'] ?>, <?= $t['lng_jemput'] ?>], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                        
                        // Marker Rumah
                        L.marker([<?= $t['lat_jemput'] ?>, <?= $t['lng_jemput'] ?>]).addTo(map).bindPopup("Lokasi Saya");

                        // Marker Driver (Jika ada update lokasi)
                        <?php if($t['lat_driver']): ?>
                            const driverIcon = L.icon({
                                iconUrl: 'https://cdn-icons-png.flaticon.com/512/1048/1048314.png', // Ikon motor/mobil
                                iconSize: [30, 30]
                            });
                            L.marker([<?= $t['lat_driver'] ?>, <?= $t['lng_driver'] ?>], {icon: driverIcon}).addTo(map).bindPopup("Posisi Driver").openPopup();
                        <?php endif; ?>
                    })();
                </script>
            </div>
            <?php endwhile; ?>


        <?php elseif($user['role'] == 'driver'): ?>
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-slate-800">Tugas Pengantaran</h2>
                <p class="text-slate-500">Update status dan lokasi secara berkala.</p>
            </div>

            <div class="space-y-6">
                <?php
                // Ambil order yg ditugaskan ke driver ini dan belum selesai
                $stmt = $pdo->prepare("SELECT o.*, u.nama as customer, u.no_hp FROM orders o JOIN users u ON o.customer_id = u.id WHERE o.driver_id = ? AND o.status != 'selesai' ORDER BY o.id ASC");
                $stmt->execute([$user['id']]);
                
                if($stmt->rowCount() == 0) echo '<div class="bg-white p-10 rounded-3xl text-center text-slate-400">Tidak ada tugas aktif.</div>';

                while($order = $stmt->fetch()): 
                    // Tentukan Logika Tombol Selanjutnya (Request 3 & 4)
                    $btn_text = "";
                    $next_code = "";
                    $show_nav = false;

                    if($order['status'] == 'dikonfirmasi') {
                        $btn_text = "Mulai Jemput Pakaian";
                        $next_code = "menjemput_pakaian";
                        $show_nav = true; // Ke lokasi customer
                    } elseif($order['status'] == 'menjemput_pakaian') {
                        $btn_text = "Sudah Dijemput -> OTW Laundry";
                        $next_code = "menuju_laundry";
                        $show_nav = true; // Ke lokasi customer (untuk jemput)
                    } elseif($order['status'] == 'menuju_laundry') {
                        $btn_text = "Sampai di Laundry (Proses Cuci)";
                        $next_code = "proses_cuci";
                        $show_nav = false; // Ke laundry (angap lokasi laundry statis)
                    } elseif($order['status'] == 'proses_cuci') {
                        $btn_text = "Selesai Cuci -> Antar ke Customer";
                        $next_code = "mengantar_bersih";
                        $show_nav = false;
                    } elseif($order['status'] == 'mengantar_bersih') {
                        $btn_text = "Selesaikan Pesanan";
                        $next_code = "selesai";
                        $show_nav = true; // Ke lokasi customer lagi
                    }
                ?>
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 relative overflow-hidden">
                    <script>
                        if (navigator.geolocation) {
                            setInterval(() => {
                                navigator.geolocation.getCurrentPosition(pos => {
                                    const formData = new FormData();
                                    formData.append('ajax_update_loc', '1');
                                    formData.append('order_id', '<?= $order['id'] ?>');
                                    formData.append('lat', pos.coords.latitude);
                                    formData.append('lng', pos.coords.longitude);
                                    fetch('dashboard.php', { method: 'POST', body: formData });
                                });
                            }, 5000); // Update lokasi setiap 5 detik
                        }
                    </script>

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="badge status-<?= $order['status'] ?> mb-2 inline-block">
                                <?= strtoupper(str_replace('_', ' ', $order['status'])) ?>
                            </span>
                            <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($order['customer']) ?></h3>
                            <p class="text-sm text-slate-500"><?= htmlspecialchars($order['alamat_jemput']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600 text-lg">Rp <?= number_format($order['total_harga']) ?></p>
                            <p class="text-xs text-slate-400"><?= $order['layanan'] ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                        <?php if($show_nav): ?>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $order['lat_jemput'] ?>,<?= $order['lng_jemput'] ?>" target="_blank" class="flex items-center justify-center gap-2 bg-green-100 text-green-700 font-bold py-3 rounded-xl hover:bg-green-200 transition">
                                <i class="fas fa-map-marked-alt"></i> Petunjuk Arah (Gmaps)
                            </a>
                        <?php endif; ?>

                        <?php if($btn_text): ?>
                            <a href="dashboard.php?update_status=<?= $next_code ?>&id=<?= $order['id'] ?>" class="flex items-center justify-center gap-2 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                                <i class="fas fa-check-circle"></i> <?= $btn_text ?>
                            </a>
                        <?php else: ?>
                            <div class="bg-gray-100 text-gray-400 py-3 rounded-xl text-center font-bold">Menunggu Proses...</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>


        <?php elseif($user['role'] == 'admin'): ?>
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-slate-800">Panel Admin</h2>
                <p class="text-slate-500">Assign driver dan pantau status laundry.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider border-b border-slate-200">
                                <th class="p-5 font-bold">Pelanggan</th>
                                <th class="p-5 font-bold">Paket</th>
                                <th class="p-5 font-bold">Status (Live)</th>
                                <th class="p-5 font-bold">Driver</th>
                                <th class="p-5 font-bold">Aksi / Map</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            // Ambil semua order
                            $stmt = $pdo->query("SELECT o.*, u.nama as customer, d.nama as driver, d.id as driver_id 
                                                FROM orders o 
                                                LEFT JOIN users u ON o.customer_id = u.id 
                                                LEFT JOIN users d ON o.driver_id = d.id 
                                                ORDER BY o.id DESC");
                            
                            // Ambil list semua driver untuk dropdown
                            $drivers = $pdo->query("SELECT * FROM users WHERE role='driver'")->fetchAll();

                            while($o = $stmt->fetch()):
                            ?>
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="p-5">
                                    <p class="font-bold text-slate-700"><?= htmlspecialchars($o['customer']) ?></p>
                                    <p class="text-xs text-slate-400"><?= $o['alamat_jemput'] ?></p>
                                </td>
                                <td class="p-5 text-sm font-bold text-blue-600">
                                    <?= htmlspecialchars($o['layanan'] ?? '-') ?>
                                </td>
                                <td class="p-5">
                                    <span class="badge status-<?= $o['status'] ?>">
                                        <?= strtoupper(str_replace('_', ' ', $o['status'])) ?>
                                    </span>
                                </td>
                                
                                <td class="p-5">
                                    <?php if($o['status'] == 'menunggu'): ?>
                                        <form method="POST" class="flex gap-2">
                                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                            <select name="driver_id" class="border border-slate-300 rounded-lg text-sm p-2 outline-none focus:border-blue-500">
                                                <option value="">Pilih Driver...</option>
                                                <?php foreach($drivers as $drv): ?>
                                                    <option value="<?= $drv['id'] ?>"><?= htmlspecialchars($drv['nama']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="assign_driver" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-blue-700">Assign</button>
                                        </form>
                                    <?php else: ?>
                                        <div class="flex items-center gap-2 text-slate-600 font-medium">
                                            <i class="fas fa-motorcycle"></i> <?= htmlspecialchars($o['driver']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="p-5">
                                    <?php if($o['status'] != 'selesai' && $o['status'] != 'menunggu' && $o['status'] != 'dibatalkan'): ?>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $o['lat_driver'] ?? $o['lat_jemput'] ?>,<?= $o['lng_driver'] ?? $o['lng_jemput'] ?>" target="_blank" class="text-blue-500 hover:text-blue-700 text-sm font-bold">
                                            <i class="fas fa-map-marker-alt animate-bounce"></i> Cek Lokasi
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-300">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($user['role'] == 'customer'): ?>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>

</body>
</html>