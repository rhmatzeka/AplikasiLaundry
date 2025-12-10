<?php require 'config/db.php'; 
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html><head><title>Riwayat</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-10">
<div class="max-w-5xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-700 mb-8">Riwayat Pesanan</h1>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY id DESC");
    $stmt->execute([$user['id']]);
    while($o = $stmt->fetch()):
    ?>
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-6">
        <p><b>Berat:</b> <?= $o['berat_kg'] ?> kg | <b>Total:</b> Rp <?= number_format($o['total_harga']) ?></p>
        <p><b>Status:</b> <span class="px-4 py-2 rounded-full text-white bg-<?= $o['status']=='selesai'?'green':($o['status']=='menunggu'?'orange':'blue') ?>-600"><?= ucfirst($o['status']) ?></span></p>
        <p class="text-gray-600"><?= $o['alamat_jemput'] ?></p>
    </div>
    <?php endwhile; ?>
</div>
</body></html>