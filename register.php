<?php 
require 'config/db.php';  // Pastikan config/db.php ada dan benar

if($_POST){
    $nama   = trim($_POST['nama']);
    $email  = trim($_POST['email']);
    $no_hp  = trim($_POST['no_hp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah ada
    $cek = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $cek->execute([$email]);
    if($cek->rowCount() > 0){
        $error = "Email sudah terdaftar!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, no_hp, role) VALUES (?, ?, ?, ?, 'customer')");
        if($stmt->execute([$nama, $email, $password, $no_hp])){
            echo "<script>alert('Registrasi berhasil! Silakan login'); window.location='login.php';</script>";
            exit;
        } else {
            $error = "Gagal menyimpan data!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register • LaundryGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>
<body class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-900 via-blue-900 to-black">
    <div class="cursor"></div>
    <div class="cursor-follow"></div>
    <div id="particles-js" class="fixed inset-0 -z-10"></div>

    <div class="relative z-10 bg-white bg-opacity-10 backdrop-blur-lg p-10 rounded-3xl shadow-2xl w-96 border border-purple-500">
        <h2 class="text-5xl font-bold text-center glow mb-8 text-cyan-400">CREATE ACCOUNT</h2>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-500 bg-opacity-80 text-white p-4 rounded-xl mb-6 text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full p-4 bg-transparent border-2 border-purple-400 rounded-xl text-white placeholder-gray-400 focus:border-cyan-400 outline-none glow" required>
            <input type="email" name="email" placeholder="Email" class="w-full p-4 bg-transparent border-2 border-purple-400 rounded-xl text-white placeholder-gray-400 focus:border-cyan-400 outline-none glow" required>
            <input type="text" name="no_hp" placeholder="No HP (628xxx)" class="w-full p-4 bg-transparent border-2 border-purple-400 rounded-xl text-white placeholder-gray-400 focus:border-cyan-400 outline-none glow" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-4 bg-transparent border-2 border-purple-400 rounded-xl text-white placeholder-gray-400 focus:border-cyan-400 outline-none glow" required>
            <button type="submit" class="w-full btn-neon py-5 text-2xl">REGISTER NOW</button>
        </form>
        <p class="text-center mt-8 text-gray-300">Sudah punya akun? <a href="login.php" class="text-cyan-400 hover:underline">Login</a></p>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>