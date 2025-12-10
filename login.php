<?php 
require 'config/db.php';  // WAJIB ADA DI ATAS!

$error = '';

if($_POST){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Cari user berdasarkan email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Cek password
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>
<body class="relative min-h-screen flex items-center justify-center bg-black">

    <!-- Custom Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follow"></div>

    <!-- Particles Background -->
    <div id="particles-js" class="fixed inset-0 -z-10"></div>

    <div class="relative z-10 card p-12 w-96 border-2 border-purple-600">
        <h2 class="text-5xl font-bold text-center glow mb-10 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">
            Login
        </h2>

        <?php if($error): ?>
            <div class="bg-red-600 bg-opacity-80 text-white p-4 rounded-xl mb-6 text-center font-bold">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-8">
            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                class="w-full p-5 bg-transparent border-2 border-purple-500 rounded-xl text-xl focus:border-cyan-400 outline-none glow placeholder-gray-500" 
                required 
                autocomplete="email"
            >
            <input 
                type="password" 
                name="password" 
                placeholder="Password" 
                class="w-full p-5 bg-transparent border-2 border-purple-500 rounded-xl text-xl focus:border-cyan-400 outline-none glow placeholder-gray-500" 
                required
            >
            <button type="submit" class="btn-neon w-full text-2xl py-6 font-bold tracking-wider">
                Masuk
            </button>
        </form>

        <p class="text-center mt-8 text-gray-400">
            Belum punya akun?
            <a href="register.php" class="text-pink-400 hover:text-pink-300 underline font-bold">
                Daftar di sini
            </a>
        </p>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>