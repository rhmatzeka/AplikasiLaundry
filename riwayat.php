<?php 
require 'config/db.php'; 

// Cek sesi login
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];

// --- LOGIKA SIMPAN ULASAN ---
if(isset($_POST['kirim_ulasan'])) {
    $order_id = $_POST['order_id'];
    $rating   = intval($_POST['rating_input']);
    $ulasan   = trim($_POST['ulasan']);

    if($rating > 0) {
        $stmt = $pdo->prepare("UPDATE orders SET rating = ?, ulasan = ? WHERE id = ? AND customer_id = ?");
        if($stmt->execute([$rating, $ulasan, $order_id, $user['id']])) {
            echo "<script>alert('Terima kasih atas ulasan Anda!'); window.location='riwayat.php';</script>";
        }
    } else {
        echo "<script>alert('Silakan pilih jumlah bintang!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan • LaundryGo</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Status Badge Colors */
        .status-menunggu { @apply bg-orange-100 text-orange-600 border border-orange-200; }
        .status-dijemput { @apply bg-blue-100 text-blue-600 border border-blue-200; }
        .status-proses { @apply bg-purple-100 text-purple-600 border border-purple-200; }
        .status-diantar { @apply bg-cyan-100 text-cyan-600 border border-cyan-200; }
        .status-selesai { @apply bg-green-100 text-green-600 border border-green-200; }
        .status-dibatalkan { @apply bg-red-100 text-red-600 border border-red-200; }

        /* Star Rating Animation */
        .star-rating i { cursor: pointer; transition: color 0.2s; }
        .star-rating i.active { color: #facc15; /* Yellow-400 */ }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 relative min-h-screen overflow-x-hidden">

    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-cyan-100 rounded-full blur-3xl opacity-50 -z-10"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed top-0 w-full z-50 shadow-sm transition-all">
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
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="pt-28 pb-12 px-6 max-w-4xl mx-auto">
        <div class="text-center mb-10" data-aos="fade-down">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2">Riwayat Transaksi</h1>
            <p class="text-slate-500">Pantau perjalanan cucian Anda di sini.</p>
        </div>

        <div class="space-y-6">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY id DESC");
            $stmt->execute([$user['id']]);
            
            if($stmt->rowCount() == 0): 
            ?>
                <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-slate-100" data-aos="fade-up">
                    <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum ada riwayat pesanan</h3>
                    <p class="text-slate-500 mb-6">Yuk, buat pesanan laundry pertamamu sekarang!</p>
                    <a href="pesan.php" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition">
                        Pesan Sekarang
                    </a>
                </div>
            <?php endif; ?>

            <?php 
            while($o = $stmt->fetch()): 
                $tanggal = isset($o['created_at']) ? date('d M Y, H:i', strtotime($o['created_at'])) : 'Baru Saja';
            ?>
            
            <div class="bg-white p-6 md:p-8 rounded-3xl shadow-lg border border-slate-100 hover:shadow-xl transition duration-300 group relative overflow-hidden" data-aos="fade-up">
                
                <div class="absolute left-0 top-0 bottom-0 w-2 
                    <?= $o['status'] == 'selesai' ? 'bg-green-500' : 
                       ($o['status'] == 'menunggu' ? 'bg-orange-400' : 
                       ($o['status'] == 'dibatalkan' ? 'bg-red-500' : 'bg-blue-500')) ?>">
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 pl-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Order ID #<?= $o['id'] ?></span>
                            <span class="text-xs text-slate-300">•</span>
                            <span class="text-xs text-slate-400"><i class="far fa-clock mr-1"></i> <?= $tanggal ?></span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Paket Laundry Kiloan</h3>
                    </div>
                    
                    <span class="px-4 py-1.5 rounded-full text-sm font-bold capitalize status-<?= $o['status'] ?>">
                        <?= $o['status'] ?>
                    </span>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 mb-5 ml-4 border border-slate-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Berat Cucian</p>
                            <p class="text-lg font-bold text-slate-700"><i class="fas fa-weight-hanging text-blue-400 mr-2"></i><?= $o['berat_kg'] ?> Kg</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Total Biaya</p>
                            <p class="text-lg font-bold text-slate-700"><i class="fas fa-tag text-green-500 mr-2"></i>Rp <?= number_format($o['total_harga']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center ml-4">
                    <div class="flex items-start gap-3 text-slate-600">
                        <i class="fas fa-map-marker-alt text-red-400 mt-1"></i>
                        <p class="text-sm leading-relaxed"><?= htmlspecialchars($o['alamat_jemput']) ?></p>
                    </div>

                    <?php if($o['status'] == 'selesai'): ?>
                        <?php if($o['rating'] > 0): ?>
                            <div class="text-right">
                                <div class="text-yellow-400 text-sm">
                                    <?php for($i=0; $i<$o['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">"<?= htmlspecialchars($o['ulasan']) ?>"</p>
                            </div>
                        <?php else: ?>
                            <button onclick="openRatingModal(<?= $o['id'] ?>)" class="px-5 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-bold rounded-xl shadow-md transition text-sm">
                                <i class="fas fa-star mr-1"></i> Beri Nilai
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="ratingModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRatingModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md animate-bounce-in">
            <h3 class="text-2xl font-bold text-center text-slate-800 mb-2">Beri Penilaian</h3>
            <p class="text-center text-slate-500 mb-6">Bagaimana kualitas layanan driver kami?</p>
            
            <form method="POST">
                <input type="hidden" name="order_id" id="modalOrderId">
                <input type="hidden" name="rating_input" id="ratingInput" value="0">
                
                <div class="flex justify-center gap-3 text-4xl text-slate-300 mb-6 star-rating" id="starContainer">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>

                <textarea name="ulasan" rows="3" class="w-full p-4 bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 mb-6" placeholder="Tulis ulasan Anda di sini... (Opsional)"></textarea>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeRatingModal()" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" name="kirim_ulasan" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // Logic Modal & Bintang
        const modal = document.getElementById('ratingModal');
        const modalOrderId = document.getElementById('modalOrderId');
        const stars = document.querySelectorAll('#starContainer i');
        const ratingInput = document.getElementById('ratingInput');

        function openRatingModal(id) {
            modalOrderId.value = id;
            modal.classList.remove('hidden');
        }

        function closeRatingModal() {
            modal.classList.add('hidden');
            resetStars();
        }

        // Handle Star Click
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                ratingInput.value = val;
                updateStars(val);
            });
        });

        function updateStars(val) {
            stars.forEach(s => {
                if(s.getAttribute('data-value') <= val) {
                    s.classList.add('active', 'text-yellow-400');
                    s.classList.remove('text-slate-300');
                } else {
                    s.classList.remove('active', 'text-yellow-400');
                    s.classList.add('text-slate-300');
                }
            });
        }
        
        function resetStars() {
            ratingInput.value = 0;
            updateStars(0);
        }
    </script>
</body>
</html>