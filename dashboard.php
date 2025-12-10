<?php 
require 'config/db.php'; 
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard • LaundryGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Status Badges */
        .badge { @apply px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider; }
        .status-menunggu { @apply bg-orange-100 text-orange-600; }
        .status-dijemput { @apply bg-blue-100 text-blue-600; }
        .status-proses { @apply bg-purple-100 text-purple-600; }
        .status-diantar { @apply bg-cyan-100 text-cyan-600; }
        .status-selesai { @apply bg-green-100 text-green-600; }
        .status-dibatalkan { @apply bg-red-100 text-red-600; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 relative overflow-x-hidden min-h-screen">

    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-cyan-100 rounded-full blur-3xl opacity-50 -z-10"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed top-0 w-full z-30 transition-all">
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
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-700">Hai, <?= htmlspecialchars($user['nama']) ?></p>
                    <p class="text-xs text-slate-500 capitalize"><?= $user['role'] ?></p>
                </div>
                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 border border-slate-200">
                    <i class="fas fa-user"></i>
                </div>
                <a href="logout.php" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl text-sm font-bold transition border border-red-100">
                    <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="pt-28 pb-12 px-6 max-w-7xl mx-auto">
        
        <?php if($user['role'] == 'customer'): ?>
            <div class="text-center mb-12" data-aos="fade-down">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2">
                    Mau nyuci apa hari ini?
                </h2>
                <p class="text-slate-500">Pilih layanan di bawah, kami jemput secepat kilat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <a href="pesan.php" class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 hover:shadow-2xl hover:border-blue-300 hover:-translate-y-2 transition duration-300 group flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center text-4xl text-blue-500 mb-6 group-hover:bg-blue-500 group-hover:text-white transition">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Pesan Laundry</h3>
                    <p class="text-slate-500">Antar jemput pakaian kotor, terima beres wangi & rapi.</p>
                    <span class="mt-6 px-6 py-2 bg-slate-100 text-slate-600 rounded-full text-sm font-bold group-hover:bg-blue-100 group-hover:text-blue-600 transition">Order Sekarang &rarr;</span>
                </a>

                <a href="riwayat.php" class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 hover:shadow-2xl hover:border-cyan-300 hover:-translate-y-2 transition duration-300 group flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-cyan-50 rounded-full flex items-center justify-center text-4xl text-cyan-500 mb-6 group-hover:bg-cyan-500 group-hover:text-white transition">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Riwayat Pesanan</h3>
                    <p class="text-slate-500">Pantau status cucianmu dan lihat transaksi sebelumnya.</p>
                    <span class="mt-6 px-6 py-2 bg-slate-100 text-slate-600 rounded-full text-sm font-bold group-hover:bg-cyan-100 group-hover:text-cyan-600 transition">Lihat Status &rarr;</span>
                </a>
            </div>

        <?php elseif($user['role'] == 'driver'): ?>
            <div class="flex justify-between items-end mb-8" data-aos="fade-down">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Job Tersedia</h2>
                    <p class="text-slate-500">Semangat jemput rezeki hari ini!</p>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200 text-sm font-bold text-slate-600">
                    <i class="fas fa-motorcycle text-blue-500 mr-2"></i> Mode Driver
                </div>
            </div>

            <div class="grid gap-6">
                <?php
                $stmt = $pdo->prepare("SELECT o.*, u.nama as customer, u.no_hp FROM orders o JOIN users u ON o.customer_id = u.id WHERE o.status = 'menunggu' OR (o.driver_id = ? AND o.status != 'selesai') ORDER BY o.id DESC");
                $stmt->execute([$user['id']]);
                
                if($stmt->rowCount() == 0): ?>
                    <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-slate-100">
                        <i class="fas fa-coffee text-6xl text-slate-200 mb-4"></i>
                        <p class="text-xl font-bold text-slate-400">Belum ada orderan masuk.</p>
                        <p class="text-slate-400 text-sm">Santai dulu sambil ngopi ☕</p>
                    </div>
                <?php endif; ?>

                <?php while($order = $stmt->fetch()): ?>
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition flex flex-col md:flex-row gap-6 items-start md:items-center relative overflow-hidden group" data-aos="fade-up">
                    <div class="absolute left-0 top-0 bottom-0 w-2 
                        <?= $order['status'] == 'menunggu' ? 'bg-orange-400' : 
                           ($order['status'] == 'dijemput' ? 'bg-blue-500' : 'bg-green-500') ?>">
                    </div>

                    <div class="flex-1 pl-4">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="badge status-<?= $order['status'] ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <span class="text-slate-400 text-sm">Order #<?= $order['id'] ?></span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($order['customer']) ?></h3>
                        <p class="text-slate-500 text-sm mb-4"><i class="fas fa-map-marker-alt text-red-400 mr-1"></i> <?= htmlspecialchars($order['alamat_jemput']) ?></p>
                        
                        <div class="flex flex-wrap gap-4 text-sm font-medium text-slate-600 bg-slate-50 p-3 rounded-xl inline-flex">
                            <span class="flex items-center gap-2"><i class="fas fa-weight-hanging text-slate-400"></i> <?= $order['berat_kg'] ?> Kg</span>
                            <span class="flex items-center gap-2"><i class="fas fa-money-bill-wave text-green-500"></i> Rp <?= number_format($order['total_harga']) ?></span>
                            <span class="flex items-center gap-2"><i class="fas fa-phone text-blue-500"></i> <?= htmlspecialchars($order['no_hp']) ?></span>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex flex-col gap-3">
                        <?php if($order['status'] == 'menunggu'): ?>
                            <a href="ambil_order.php?id=<?= $order['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-xl font-bold shadow-lg shadow-blue-200 transition text-center flex items-center justify-center gap-2">
                                <i class="fas fa-hand-paper"></i> Ambil Order
                            </a>
                        <?php else: ?>
                            <a href="update_status.php?id=<?= $order['id'] ?>" class="bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white py-3 px-8 rounded-xl font-bold transition text-center flex items-center justify-center gap-2">
                                <i class="fas fa-sync-alt"></i> Update Status
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

        <?php elseif($user['role'] == 'admin'): ?>
            <div class="mb-8" data-aos="fade-down">
                <h2 class="text-3xl font-bold text-slate-800">Panel Admin</h2>
                <p class="text-slate-500">Ringkasan semua transaksi laundry.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden" data-aos="fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider border-b border-slate-200">
                                <th class="p-5 font-bold">ID</th>
                                <th class="p-5 font-bold">Pelanggan</th>
                                <th class="p-5 font-bold">Detail</th>
                                <th class="p-5 font-bold">Status</th>
                                <th class="p-5 font-bold">Driver</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $stmt = $pdo->query("SELECT o.*, u.nama as customer, d.nama as driver FROM orders o 
                                                LEFT JOIN users u ON o.customer_id = u.id 
                                                LEFT JOIN users d ON o.driver_id = d.id 
                                                ORDER BY o.id DESC");
                            while($o = $stmt->fetch()):
                            ?>
                            <tr class="hover:bg-blue-50/50 transition duration-150">
                                <td class="p-5 font-bold text-slate-400">#<?= $o['id'] ?></td>
                                <td class="p-5">
                                    <p class="font-bold text-slate-700"><?= htmlspecialchars($o['customer'] ?? '-') ?></p>
                                    <p class="text-xs text-slate-400">Customer</p>
                                </td>
                                <td class="p-5">
                                    <p class="font-bold text-slate-700"><?= $o['berat_kg'] ?> kg</p>
                                    <p class="text-sm text-green-600 font-medium">Rp <?= number_format($o['total_harga']) ?></p>
                                </td>
                                <td class="p-5">
                                    <span class="badge status-<?= $o['status'] ?>">
                                        <?= ucfirst($o['status']) ?>
                                    </span>
                                </td>
                                <td class="p-5 text-slate-600">
                                    <?php if($o['driver']): ?>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-motorcycle text-slate-400"></i>
                                            <?= htmlspecialchars($o['driver']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-sm italic">Menunggu Driver</span>
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>