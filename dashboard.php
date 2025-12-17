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
    $stmt = $pdo->prepare("UPDATE orders SET driver_id = ?, status = 'dikonfirmasi' WHERE id = ?");
    $stmt->execute([$driver_id, $order_id]);
    echo "<script>alert('Driver berhasil ditugaskan!'); window.location='dashboard.php';</script>";
}

// --- LOGIKA DRIVER UPDATE STATUS ---
if($user['role'] == 'driver' && isset($_GET['update_status']) && isset($_GET['id'])) {
    $oid = $_GET['id'];
    $next_status = $_GET['update_status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ? AND driver_id = ?");
    $stmt->execute([$next_status, $oid, $user['id']]);
    header("Location: dashboard.php");
    exit;
}

// --- LOGIKA DRIVER UPDATE LOKASI (AJAX) ---
if(isset($_POST['ajax_update_loc']) && $user['role'] == 'driver') {
    $oid = $_POST['order_id'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $stmt = $pdo->prepare("UPDATE orders SET lat_driver = ?, lng_driver = ? WHERE id = ?");
    $stmt->execute([$lat, $lng, $oid]);
    exit;
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
        .status-menunggu { @apply bg-gray-200 text-gray-600; }
        .status-dikonfirmasi { @apply bg-yellow-100 text-yellow-600; }
        .status-menjemput_pakaian { @apply bg-orange-100 text-orange-600; }
        .status-menuju_laundry { @apply bg-purple-100 text-purple-600; }
        .status-proses_cuci { @apply bg-blue-100 text-blue-600; }
        .status-mengantar_bersih { @apply bg-indigo-100 text-indigo-600; }
        .status-selesai { @apply bg-green-100 text-green-600; }
        
        #chatBox::-webkit-scrollbar { width: 5px; }
        #chatBox::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; }
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
                    <div>
                        <span class="badge status-<?= $t['status'] ?>"><?= strtoupper(str_replace('_', ' ', $t['status'])) ?></span>
                        <span class="text-xs font-mono ml-2">Order #<?= $t['id'] ?></span>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="openChat(<?= $t['id'] ?>, 'driver')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-md flex items-center gap-1">
                            <i class="fas fa-motorcycle"></i> Driver
                        </button>
                        <button onclick="openChat(<?= $t['id'] ?>, 'admin')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-md flex items-center gap-1">
                            <i class="fas fa-user-shield"></i> Admin
                        </button>
                    </div>
                </div>
                <div id="map_<?= $t['id'] ?>" class="h-64 rounded-xl z-0"></div>
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    (function(){
                        const map = L.map('map_<?= $t['id'] ?>').setView([<?= $t['lat_jemput'] ?>, <?= $t['lng_jemput'] ?>], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                        L.marker([<?= $t['lat_jemput'] ?>, <?= $t['lng_jemput'] ?>]).addTo(map).bindPopup("Lokasi Saya");
                        <?php if($t['lat_driver']): ?>
                            const driverIcon = L.icon({iconUrl: 'https://cdn-icons-png.flaticon.com/512/1048/1048314.png', iconSize: [30, 30]});
                            L.marker([<?= $t['lat_driver'] ?>, <?= $t['lng_driver'] ?>], {icon: driverIcon}).addTo(map).bindPopup("Posisi Driver").openPopup();
                        <?php endif; ?>
                    })();
                </script>
            </div>
            <?php endwhile; ?>


        <?php elseif($user['role'] == 'driver'): ?>
            <div class="mb-8"><h2 class="text-3xl font-bold text-slate-800">Tugas Pengantaran</h2></div>
            <div class="space-y-6">
                <?php
                $stmt = $pdo->prepare("SELECT o.*, u.nama as customer FROM orders o JOIN users u ON o.customer_id = u.id WHERE o.driver_id = ? AND o.status != 'selesai' ORDER BY o.id ASC");
                $stmt->execute([$user['id']]);
                if($stmt->rowCount() == 0) echo '<div class="bg-white p-10 rounded-3xl text-center text-slate-400">Tidak ada tugas aktif.</div>';

                while($order = $stmt->fetch()): 
                    $btn_text = ""; $next_code = ""; $show_nav = false;
                    if($order['status'] == 'dikonfirmasi') { $btn_text = "Mulai Jemput"; $next_code = "menjemput_pakaian"; $show_nav = true; }
                    elseif($order['status'] == 'menjemput_pakaian') { $btn_text = "OTW Laundry"; $next_code = "menuju_laundry"; $show_nav = true; }
                    elseif($order['status'] == 'menuju_laundry') { $btn_text = "Sampai (Proses Cuci)"; $next_code = "proses_cuci"; }
                    elseif($order['status'] == 'proses_cuci') { $btn_text = "Antar ke Customer"; $next_code = "mengantar_bersih"; }
                    elseif($order['status'] == 'mengantar_bersih') { $btn_text = "Selesaikan"; $next_code = "selesai"; $show_nav = true; }
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
                            }, 5000);
                        }
                    </script>

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="badge status-<?= $order['status'] ?> mb-2 inline-block"><?= strtoupper(str_replace('_', ' ', $order['status'])) ?></span>
                            <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($order['customer']) ?></h3>
                            <p class="text-sm text-slate-500"><?= htmlspecialchars($order['alamat_jemput']) ?></p>
                        </div>
                        
                        <button onclick="openChat(<?= $order['id'] ?>, 'driver')" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-200 transition flex items-center gap-2">
                            <i class="fas fa-comment-dots"></i> Chat Customer
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                        <?php if($show_nav): ?>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $order['lat_jemput'] ?>,<?= $order['lng_jemput'] ?>" target="_blank" class="flex items-center justify-center gap-2 bg-green-100 text-green-700 font-bold py-3 rounded-xl hover:bg-green-200 transition"><i class="fas fa-map-marked-alt"></i> Petunjuk Arah</a>
                        <?php endif; ?>
                        <?php if($btn_text): ?>
                            <a href="dashboard.php?update_status=<?= $next_code ?>&id=<?= $order['id'] ?>" class="flex items-center justify-center gap-2 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition"><i class="fas fa-check-circle"></i> <?= $btn_text ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>


        <?php elseif($user['role'] == 'admin'): ?>
            <div class="mb-8"><h2 class="text-3xl font-bold text-slate-800">Panel Admin</h2></div>
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider border-b border-slate-200">
                                <th class="p-5 font-bold">Pelanggan</th>
                                <th class="p-5 font-bold">Paket</th>
                                <th class="p-5 font-bold">Status</th>
                                <th class="p-5 font-bold">Driver</th>
                                <th class="p-5 font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $stmt = $pdo->query("SELECT o.*, u.nama as customer, d.nama as driver FROM orders o LEFT JOIN users u ON o.customer_id = u.id LEFT JOIN users d ON o.driver_id = d.id ORDER BY o.id DESC");
                            $drivers = $pdo->query("SELECT * FROM users WHERE role='driver'")->fetchAll();
                            while($o = $stmt->fetch()):
                            ?>
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="p-5">
                                    <p class="font-bold text-slate-700"><?= htmlspecialchars($o['customer']) ?></p>
                                    <p class="text-xs text-slate-400">Order #<?= $o['id'] ?></p>
                                </td>
                                <td class="p-5 text-sm font-bold text-blue-600"><?= htmlspecialchars($o['layanan'] ?? '-') ?></td>
                                <td class="p-5"><span class="badge status-<?= $o['status'] ?>"><?= strtoupper(str_replace('_', ' ', $o['status'])) ?></span></td>
                                <td class="p-5">
                                    <?php if($o['status'] == 'menunggu'): ?>
                                        <form method="POST" class="flex gap-2">
                                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                            <select name="driver_id" class="border border-slate-300 rounded-lg text-sm p-2 outline-none"><option value="">Pilih Driver...</option><?php foreach($drivers as $drv): ?><option value="<?= $drv['id'] ?>"><?= htmlspecialchars($drv['nama']) ?></option><?php endforeach; ?></select>
                                            <button type="submit" name="assign_driver" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-blue-700">Assign</button>
                                        </form>
                                    <?php else: ?>
                                        <div class="text-slate-600 font-medium"><i class="fas fa-motorcycle"></i> <?= htmlspecialchars($o['driver']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="p-5 flex gap-2">
                                    <?php if($o['status'] != 'selesai' && $o['status'] != 'menunggu'): ?>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $o['lat_driver'] ?? $o['lat_jemput'] ?>,<?= $o['lng_driver'] ?? $o['lng_jemput'] ?>" target="_blank" class="text-blue-500 hover:text-blue-700 text-sm font-bold"><i class="fas fa-map-marker-alt"></i> Cek Lokasi</a>
                                        
                                        <button onclick="openChat(<?= $o['id'] ?>, 'admin')" class="text-green-500 hover:text-green-700 text-sm font-bold flex items-center gap-1">
                                            <i class="fas fa-comments"></i> Chat
                                        </button>
                                    <?php else: ?> - <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div id="chatModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeChat()"></div>
        <div class="absolute bottom-0 md:bottom-10 right-0 md:right-10 w-full md:w-96 bg-white md:rounded-3xl rounded-t-3xl shadow-2xl overflow-hidden flex flex-col h-[80vh] md:h-[600px] animate-slide-up">
            
            <div class="bg-blue-600 p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center"><i class="fas fa-comments"></i></div>
                    <div>
                        <h3 class="font-bold text-lg">Live Chat</h3>
                        <p id="chatRoomTitle" class="text-xs text-blue-100">Loading...</p>
                    </div>
                </div>
                <button onclick="closeChat()" class="text-white hover:bg-white/20 p-2 rounded-full"><i class="fas fa-times"></i></button>
            </div>

            <div id="chatBox" class="flex-1 p-4 overflow-y-auto bg-slate-50">
                <div class="text-center text-gray-400 mt-10"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>
            </div>

            <div class="p-4 bg-white border-t border-slate-100">
                <form id="chatForm" class="flex gap-2">
                    <input type="hidden" id="chatOrderId">
                    <input type="hidden" id="chatRoomType">
                    <input type="text" id="chatInput" class="flex-1 bg-slate-100 border-0 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Tulis pesan..." autocomplete="off">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-12 h-12 rounded-xl flex items-center justify-center transition shadow-lg">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php if($user['role'] == 'customer'): ?>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>

    <script>
        const chatModal = document.getElementById('chatModal');
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const chatOrderIdInput = document.getElementById('chatOrderId');
        const chatRoomTypeInput = document.getElementById('chatRoomType');
        const chatRoomTitle = document.getElementById('chatRoomTitle');
        let chatInterval;

        function openChat(orderId, roomType) {
            chatModal.classList.remove('hidden');
            chatOrderIdInput.value = orderId;
            chatRoomTypeInput.value = roomType;
            chatRoomTitle.innerText = "Chat dengan " + roomType.toUpperCase();
            
            loadChat(orderId, roomType);
            
            if(chatInterval) clearInterval(chatInterval);
            chatInterval = setInterval(() => loadChat(orderId, roomType), 2000);
        }

        function closeChat() {
            chatModal.classList.add('hidden');
            clearInterval(chatInterval);
        }

        function loadChat(orderId, roomType) {
            const formData = new FormData();
            formData.append('action', 'get');
            formData.append('order_id', orderId);
            formData.append('room_type', roomType);

            fetch('api_chat.php', { method: 'POST', body: formData })
                .then(res => res.text())
                .then(data => {
                    // Cek apakah user sedang scroll ke atas (untuk tidak mengganggu membaca)
                    const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 10;
                    
                    chatBox.innerHTML = data;
                    
                    if(isScrolledToBottom) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            const orderId = chatOrderIdInput.value;
            const roomType = chatRoomTypeInput.value;

            if(message) {
                const formData = new FormData();
                formData.append('action', 'send');
                formData.append('order_id', orderId);
                formData.append('room_type', roomType);
                formData.append('message', message);

                fetch('api_chat.php', { method: 'POST', body: formData })
                    .then(() => {
                        chatInput.value = '';
                        loadChat(orderId, roomType); 
                        setTimeout(() => chatBox.scrollTop = chatBox.scrollHeight, 100);
                    });
            }
        });
    </script>

</body>
</html>