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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .status-menunggu { background: #f97316; }
        .status-dijemput { background: #3b82f6; }
        .status-proses { background: #8b5cf6; }
        .status-diantar { background: #06b6d4; }
        .status-selesai { background: #10b981; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-2xl fixed top-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <h1 class="text-2xl sm:text-3xl font-bold">LaundryGo</h1>
            </div>
            <div class="text-center sm:text-right text-sm sm:text-base">
                <span class="font-semibold">Hai, <?= htmlspecialchars($user['nama']) ?></span>
                <span class="mx-2 text-gray-200"></span>
                <span class="mx-2 text-gray-200"></span>
                <a href="logout.php" class="underline hover:text-yellow-300 font-medium">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-6 max-w-7xl mx-auto">
        
        <?php if($user['role'] == 'customer'): ?>
            <div class="text-center mb-12">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-4" data-aos="fade-down">
                    Selamat Datang Kembali,<br>
                    <span class="text-purple-600"><?= htmlspecialchars($user['nama']) ?>!</span>
                </h2>
                <p class="text-gray-600 text-lg">Pilih layanan yang kamu butuhkan</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <a href="pesan.php" class="bg-white rounded-3xl shadow-xl p-10 text-center hover:shadow-2xl hover:scale-105 transition-all duration-300 group" data-aos="zoom-in">
                    <div class="text-7xl sm:text-8xl mb-6 text-purple-600 group-hover:text-purple-700">
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">Pesan Laundry</h3>
                    <p class="text-gray-600">Antar jemput langsung ke rumahmu</p>
                </a>

                <a href="riwayat.php" class="bg-white rounded-3xl shadow-xl p-10 text-center hover:shadow-2xl hover:scale-105 transition-all duration-300 group" data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-7xl sm:text-8xl mb-6 text-blue-600 group-hover:text-blue-700">
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">Riwayat Pesanan</h3>
                    <p class="text-gray-600">Lacak status laundry kamu</p>
                </a>
            </div>

        <?php elseif($user['role'] == 'driver'): ?>
            <h2 class="text-3xl sm:text-5xl font-bold text-center text-gray-800 mb-10" data-aos="fade-down">
                Order Tersedia
            </h2>

            <div class="space-y-6">
                <?php
                $stmt = $pdo->prepare("SELECT o.*, u.nama as customer, u.no_hp FROM orders o JOIN users u ON o.customer_id = u.id WHERE o.status = 'menunggu' OR (o.driver_id = ? AND o.status != 'selesai') ORDER BY o.id DESC");
                $stmt->execute([$user['id']]);
                if($stmt->rowCount() == 0): ?>
                    <div class="text-center py-20">
                        <p class="text-2xl text-gray-500">Belum ada order saat ini.</p>
                        <p class="text-gray-400 mt-4">Tunggu order baru masuk ya!</p>
                    </div>
                <?php endif; ?>

                <?php while($order = $stmt->fetch()): ?>
                <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 border-l-8 border-purple-600" data-aos="fade-up">
                    <div class="flex-1">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800"><?= htmlspecialchars($order['customer']) ?></h3>
                        <p class="text-gray-600 mt-1">HP: <?= htmlspecialchars($order['no_hp']) ?></p>
                        <div class="mt-3 space-y-2 text-sm sm:text-base">
                            <p><strong>Berat:</strong> <?= $order['berat_kg'] ?> kg</p>
                            <p><strong>Total:</strong> Rp <?= number_format($order['total_harga']) ?></p>
                            <p><strong>Alamat:</strong> <?= htmlspecialchars($order['alamat_jemput']) ?></p>
                        </div>
                        <span class="inline-block mt-4 px-5 py-2 rounded-full text-white font-bold text-sm sm:text-base status-<?= $order['status'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                        </span>
                    </div>
                    <div class="flex flex-col gap-3 w-full sm:w-auto">
                        <?php if($order['status'] == 'menunggu'): ?>
                            <a href="ambil_order.php?id=<?= $order['id'] ?>" class="bg-green-600 hover:bg-green-700 text-white text-center py-4 px-8 rounded-xl font-bold text-lg shadow-lg transition">
                                Ambil Order
                            </a>
                        <?php else: ?>
                            <a href="update_status.php?id=<?= $order['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-4 px-8 rounded-xl font-bold text-lg shadow-lg transition">
                                Update Status
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

        <?php elseif($user['role'] == 'admin'): ?>
            <h2 class="text-3xl sm:text-5xl font-bold text-center text-gray-800 mb-10" data-aos="fade-down">
                Dashboard Admin
            </h2>

            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6">
                    <h3 class="text-2xl sm:text-3xl font-bold">Daftar Semua Pesanan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="p-4 font-semibold">ID</th>
                                <th class="p-4 font-semibold">Customer</th>
                                <th class="p-4 font-semibold">Berat</th>
                                <th class="p-4 font-semibold">Total</th>
                                <th class="p-4 font-semibold">Status</th>
                                <th class="p-4 font-semibold">Driver</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            $stmt = $pdo->query("SELECT o.*, u.nama as customer, d.nama as driver FROM orders o 
                                                LEFT JOIN users u ON o.customer_id = u.id 
                                                LEFT JOIN users d ON o.driver_id = d.id 
                                                ORDER BY o.id DESC");
                            while($o = $stmt->fetch()):
                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-medium">#<?= $o['id'] ?></td>
                                <td class="p-4"><?= htmlspecialchars($o['customer'] ?? '-') ?></td>
                                <td class="p-4"><?= $o['berat_kg'] ?> kg</td>
                                <td class="p-4 font-medium">Rp <?= number_format($o['total_harga']) ?></td>
                                <td class="p-4">
                                    <span class="px-4 py-2 rounded-full text-white text-sm font-bold status-<?= $o['status'] ?>">
                                        <?= ucfirst($o['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4"><?= htmlspecialchars($o['driver'] ?? '-') ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>