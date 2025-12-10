<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAUNDRYGO • Robotic Laundry Revolution</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="relative text-white overflow-x-hidden">

    <!-- Custom Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follow"></div>
    <div id="particles-js" class="fixed inset-0 -z-10"></div>

    <!-- Navbar -->
    <nav class="navbar fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <h1 class="text-3xl md:text-5xl font-black glow bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-purple-600">
                LAUNDRY<span class="text-pink-400">GO</span>
            </h1>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6 lg:space-x-10 text-lg">
                <a href="#about" class="hover:text-cyan-400 transition">About</a>
                <a href="#pricing" class="hover:text-cyan-400 transition">Harga</a>
                <a href="#features" class="hover:text-cyan-400 transition">Fitur</a>
                <a href="login.php" class="btn-neon text-sm lg:text-lg px-6 py-3">Login</a>
                <a href="register.php" class="btn-neon bg-cyan-500 hover:bg-cyan-600 text-sm lg:text-lg px-6 py-3">Daftar</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-btn" class="md:hidden text-3xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-black bg-opacity-95 absolute top-full left-0 w-full border-t border-purple-800">
            <div class="flex flex-col py-6 space-y-6 text-center text-xl">
                <a href="#about" class="hover:text-cyan-400 transition">About</a>
                <a href="#pricing" class="hover:text-cyan-400 transition">Harga</a>
                <a href="#features" class="hover:text-cyan-400 transition">Fitur</a>
                <a href="login.php" class="btn-neon mx-20 py-4">Login</a>
                <a href="register.php" class="btn-neon bg-cyan-500 hover:bg-cyan-600 mx-20 py-4">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="min-h-screen flex items-center justify-center text-center px-6 pt-20">
        <div data-aos="zoom-in">
            <h1 class="text-5xl sm:text-7xl md:text-8xl lg:text-9xl font-black glow leading-tight">
                <span class="text-cyan-400">LAUNDRY</span><br>
                <span class="text-purple-400">AJA</span><br>
                <span class="text-pink-400 text-4xl sm:text-6xl md:text-7xl">REVOLUTION</span>
            </h1>
            <p class="text-xl sm:text-3xl md:text-4xl mt-8 mb-6 opacity-90" id="typing"></p>
            <p class="text-lg sm:text-2xl mb-10 opacity-80">Cuci pakaian jadi semudah kirim chat 24/7 Tanpa ribet</p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="register.php" class="btn-neon text-xl sm:text-2xl px-12 py-5">MULAI SEKARANG</a>
                <a href="#pricing" class="btn-neon bg-transparent border-2 border-cyan-400 text-cyan-400 hover:bg-cyan-400 hover:text-black text-xl sm:text-2xl px-12 py-5">Lihat Harga</a>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="py-20 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center">
            <div data-aos="fade-up">
                <h2 class="text-4xl sm:text-6xl font-black glow mb-8">Tentang <span class="text-cyan-400">LAUNDRYGO</span></h2>
                <p class="text-lg sm:text-xl leading-relaxed mb-6">
                    Didirikan tahun 2025, LAUNDRYGO adalah startup teknologi laundry pertama di Indonesia yang menggabungkan <b>AI, Robot Drone, dan IoT</b> untuk memberikan pengalaman mencuci pakaian yang belum pernah ada sebelumnya.
                </p>
                <p class="text-lg sm:text-xl leading-relaxed">
                    Kami bukan laundry biasa. Kami adalah <b>masa depan kebersihan</b>.
                </p>
                <div class="mt-10 grid grid-cols-3 gap-6 text-center">
                    <div><h3 class="text-4xl sm:text-5xl font-black text-cyan-400">50K+</h3><p class="text-sm">Pelanggan</p></div>
                    <div><h3 class="text-4xl sm:text-5xl font-black text-purple-400">127</h3><p class="text-sm">Robot Aktif</p></div>
                    <div><h3 class="text-4xl sm:text-5xl font-black text-pink-400">4.9</h3><p class="text-sm">Rating</p></div>
                </div>
            </div>
            <div data-aos="fade-up" class="text-center">
                <div class="bg-gradient-to-br from-cyan-500 to-purple-600 p-8 sm:p-10 rounded-3xl shadow-2xl">
                    <i class="fas fa-robot text-6xl sm:text-9xl mb-6"></i>
                    <h3 class="text-2xl sm:text-4xl font-bold">Powered by AI & Robotics</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section id="pricing" class="py-20 px-6 bg-black bg-opacity-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-5xl sm:text-7xl font-black text-center glow mb-16">Pilih Paket Anda</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Basic -->
                <div class="card p-8 text-center" data-aos="zoom-in">
                    <h3 class="text-3xl sm:text-4xl font-bold mb-4">BASIC</h3>
                    <p class="text-5xl sm:text-6xl font-black my-6">Rp7.000<span class="text-xl sm:text-2xl">/kg</span></p>
                    <ul class="space-y-3 mb-8 text-left text-sm sm:text-base">
                        <li>Antar Jemput Gratis</li>
                        <li>Cuci + Kering</li>
                        <li>Setrika Rapi</li>
                        <li>Wangikan</li>
                        <li>Estimasi 2 Hari</li>
                    </ul>
                    <a href="register.php" class="btn-neon w-full py-4 text-lg">Pilih Basic</a>
                </div>

                <!-- Premium -->
                <div class="card p-8 text-center border-4 border-cyan-400" data-aos="zoom-in" data-aos-delay="200">
                    <div class="bg-cyan-400 text-black px-6 py-2 rounded-full inline-block mb-4 text-sm">REKOMENDASI</div>
                    <h3 class="text-4xl sm:text-5xl font-bold mb-4">PREMIUM</h3>
                    <p class="text-6xl sm:text-7xl font-black my-6">Rp12.000<span class="text-2xl">/kg</span></p>
                    <ul class="space-y-3 mb-8 text-left text-sm sm:text-base">
                        <li>Semua fitur Basic</li>
                        <li>Express 6 Jam Jadi</li>
                        <li>Parfum Import</li>
                        <li>Lipatan Premium</li>
                        <li>Packing Exclusive</li>
                        <li>Prioritas Driver</li>
                    </ul>
                    <a href="register.php" class="btn-neon w-full py-5 text-xl bg-cyan-400 hover:bg-cyan-300 text-black">Pilih Premium</a>
                </div>

                <!-- Enterprise -->
                <div class="card p-8 text-center" data-aos="zoom-in" data-aos-delay="400">
                    <h3 class="text-3xl sm:text-4xl font-bold mb-4">ENTERPRISE</h3>
                    <p class="text-5xl sm:text-6xl font-black my-6">Custom</p>
                    <p class="text-lg sm:text-xl mb-6">Untuk Kost • Hotel • Kantor</p>
                    <ul class="space-y-3 mb-8 text-left text-sm sm:text-base">
                        <li>Kapasitas 100kg+/hari</li>
                        <li>Jadwal Rutin</li>
                        <li>Driver Khusus</li>
                        <li>Laporan Digital</li>
                        <li>Harga Spesial</li>
                    </ul>
                    <a href="https://wa.me/628123456789" class="btn-neon w-full py-4 text-lg">Hubungi Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-5xl sm:text-7xl font-black text-center glow mb-16">Fitur Canggih</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="card p-6 text-center" data-aos="fade-up">
                    <i class="fas fa-drone text-5xl sm:text-6xl mb-4 text-cyan-400"></i>
                    <h3 class="text-lg sm:text-2xl font-bold">Drone Pickup</h3>
                </div>
                <div class="card p-6 text-center" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-map-marked-alt text-5xl sm:text-6xl mb-4 text-purple-400"></i>
                    <h3 class="text-lg sm:text-2xl font-bold">Live Tracking</h3>
                </div>
                <div class="card p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-bolt text-5xl sm:text-6xl mb-4 text-pink-400"></i>
                    <h3 class="text-lg sm:text-2xl font-bold">6 Jam Jadi</h3>
                </div>
                <div class="card p-6 text-center" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-shield-alt text-5xl sm:text-6xl mb-4 text-green-400"></i>
                    <h3 class="text-lg sm:text-2xl font-bold">100% Aman</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-black bg-opacity-70 py-12 px-6 border-t border-purple-800">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl font-black glow mb-4">LAUNDRYGO</h1>
            <p class="text-lg sm:text-xl mb-4">Masa Depan Laundry Sudah Tiba © 2025</p>
            <p class="text-sm sm:text-base">Made with untuk Indonesia Bersih</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/script.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({duration: 1000, once: true});

        // Mobile Menu Toggle
        document.getElementById('menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Typing Animation
        function typeWriter(element, text, speed = 80) {
            let i = 0;
            element.innerHTML = '';
            const timer = setInterval(() => {
                element.innerHTML += text.charAt(i);
                i++;
                if (i > text.length) clearInterval(timer);
            }, speed);
        }
        setTimeout(() => typeWriter(document.getElementById('typing'), "Cuci pakaian jadi semudah kirim chat.", 80), 1000);
    </script>
</body>
</html>