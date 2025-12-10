<?php 
require 'config/db.php'; 

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
    <title>Masuk Akun • LaundryGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-cyan-100 rounded-full blur-3xl opacity-50 -z-10"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-md border border-slate-100 relative z-10">
        
        <div class="text-center mb-8">
            <a href="index.php" class="inline-flex items-center gap-2 mb-4 group">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition">
                    <i class="fas fa-soap"></i>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Laundry<span class="text-blue-500">Go</span>
                </h1>
            </a>
            <h2 class="text-xl font-bold text-slate-700">Selamat Datang Kembali!</h2>
            <p class="text-slate-400 text-sm mt-1">Silakan masuk untuk mengelola laundry Anda.</p>
        </div>

        <?php if($error): ?>
            <div class="flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl mb-6 text-sm font-medium animate-pulse">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            
            <div>
                <label class="block text-slate-600 text-sm font-semibold mb-2 ml-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-400"></i>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="nama@email.com" 
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition text-slate-700 font-medium placeholder-slate-400" 
                        required 
                        autocomplete="email"
                    >
                </div>
            </div>

            <div>
                <label class="block text-slate-600 text-sm font-semibold mb-2 ml-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-400"></i>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="••••••••" 
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition text-slate-700 font-medium placeholder-slate-400" 
                        required
                    >
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5">
                Masuk Sekarang <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="text-center mt-8 text-slate-500 text-sm">
            Belum punya akun? 
            <a href="register.php" class="text-blue-600 hover:text-blue-700 font-bold hover:underline transition">
                Daftar Gratis
            </a>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="text-slate-400 hover:text-slate-600 text-xs font-medium transition flex items-center justify-center gap-1">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

    </div>

</body>
</html>