<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAUNDRYGO • Kebersihan Masa Depan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Custom Blue Gradient Text */
        .text-gradient {
            background: linear-gradient(to right, #0284c7, #2dd4bf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Bubble Background Decoration */
        .bubble {
            position: absolute;
            background: rgba(56, 189, 248, 0.1);
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 overflow-x-hidden">

    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    <i class="fas fa-soap"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    Laundry<span class="text-blue-500">Go</span>
                </h1>
            </a>

            <div class="hidden md:flex items-center space-x-8 font-medium text-slate-600">
                <a href="#about" class="hover:text-blue-500 transition">Tentang</a>
                <a href="#pricing" class="hover:text-blue-500 transition">Harga</a>
                <a href="#features" class="hover:text-blue-500 transition">Keunggulan</a>
                <div class="flex items-center gap-3 ml-4">
                    <a href="login.php" class="px-5 py-2.5 text-blue-600 font-semibold hover:bg-blue-50 rounded-full transition">Masuk</a>
                    <a href="register.php" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow-md shadow-blue-200 transition transform hover:-translate-y-0.5">Daftar Sekarang</a>
                </div>
            </div>

            <button id="menu-btn" class="md:hidden text-2xl text-slate-600">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-100 absolute top-full left-0 w-full shadow-lg">
            <div class="flex flex-col py-6 space-y-4 px-6">
                <a href="#about" class="text-slate-600 font-medium">Tentang</a>
                <a href="#pricing" class="text-slate-600 font-medium">Harga</a>
                <a href="#features" class="text-slate-600 font-medium">Keunggulan</a>
                <hr class="border-slate-100">
                <a href="login.php" class="text-center w-full py-3 text-blue-600 font-bold border border-blue-100 rounded-xl">Masuk</a>
                <a href="register.php" class="text-center w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200">Daftar</a>
            </div>
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-cyan-100 rounded-full blur-3xl opacity-50 -z-10"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-50 -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-sm font-bold mb-6 border border-blue-100">
                    <i class="fas fa-bolt"></i> Revolusi Laundry Digital #1
                </div>
                <h1 class="text-5xl md:text-7xl font-black leading-tight text-slate-900 mb-6">
                    Cuci Baju <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-400">Tanpa Ribet.</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-500 mb-8 leading-relaxed max-w-lg">
                    Nikmati layanan laundry antar-jemput berbasis teknologi. Cukup order dari HP, driver & robot kami yang bekerja. Wangi, Bersih, Praktis.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="login.php" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-full shadow-xl shadow-blue-200 transition transform hover:-translate-y-1 text-center">
                        <i class="fas fa-rocket mr-2"></i> Mulai Laundry
                    </a>
                    <a href="#pricing" class="px-8 py-4 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-lg font-bold rounded-full shadow-sm transition text-center">
                        Lihat Paket
                    </a>
                </div>
                
                <div class="mt-10 flex items-center gap-4 text-sm font-medium text-slate-500">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white"></div>
                        <div class="w-10 h-10 rounded-full bg-slate-300 border-2 border-white"></div>
                        <div class="w-10 h-10 rounded-full bg-slate-400 border-2 border-white"></div>
                    </div>
                    <p>Dipercaya oleh 50.000+ Pelanggan</p>
                </div>
            </div>

            <div class="relative hidden md:block" data-aos="fade-left">
                <div class="relative z-10 bg-white p-6 rounded-3xl shadow-2xl border border-slate-100 rotate-2 hover:rotate-0 transition duration-500">
                   <div class="bg-blue-50 rounded-2xl h-96 flex items-center justify-center overflow-hidden relative">
                        <i class="fas fa-tshirt text-9xl text-blue-200/50 absolute"></i>
                        <div class="z-10 text-center">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg text-blue-500 text-4xl">
                                <i class="fas fa-check"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-800">Selesai Dicuci</h3>
                            <p class="text-slate-500">Paket Premium • 4kg</p>
                        </div>
                   </div>
                   <div class="absolute -left-10 top-10 bg-white p-4 rounded-2xl shadow-xl border border-slate-50 flex items-center gap-3 animate-bounce">
                       <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                           <i class="fas fa-truck"></i>
                       </div>
                       <div>
                           <p class="text-xs text-slate-400 font-bold">STATUS</p>
                           <p class="font-bold text-slate-800">Sedang Dijemput</p>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-blue-600 font-bold tracking-wider uppercase text-sm">Kenapa Kami?</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mt-3 mb-4">Teknologi Canggih untuk <br> Pakaian Kesayangan.</h2>
                <p class="text-slate-500 text-lg">Kami menggabungkan mesin cuci industrial terbaik dengan sistem tracking real-time.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-blue-50 transition duration-300 border border-slate-100 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl text-blue-500 mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Express 6 Jam</h3>
                    <p class="text-slate-500 leading-relaxed">Butuh cepat? Layanan kilat kami siap mencuci dan menyetrika dalam hitungan jam.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-blue-50 transition duration-300 border border-slate-100 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl text-cyan-500 mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-map-location-dot"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Live Tracking</h3>
                    <p class="text-slate-500 leading-relaxed">Pantau status cucian dan lokasi driver secara real-time langsung dari aplikasi.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-blue-50 transition duration-300 border border-slate-100 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl text-purple-500 mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">AI Sorting</h3>
                    <p class="text-slate-500 leading-relaxed">Teknologi AI memilah jenis kain untuk memastikan perawatan pencucian yang tepat.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-blue-50 transition duration-300 border border-slate-100 group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl text-green-500 mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-shield-heart"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Higienis & Aman</h3>
                    <p class="text-slate-500 leading-relaxed">Detergen premium anti-bakteri dan jaminan ganti rugi jika pakaian rusak/hilang.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-20 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <h2 class="text-4xl md:text-5xl font-black text-center text-slate-900 mb-16">Pilihan Paket Hemat</h2>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 hover:-translate-y-2 transition duration-300" data-aos="fade-up">
                    <h3 class="text-lg font-bold text-slate-500 uppercase tracking-widest mb-4">Kiloan Hemat</h3>
                    <div class="flex items-baseline mb-6">
                        <span class="text-4xl font-black text-slate-800">Rp7.000</span>
                        <span class="text-slate-500 ml-2">/kg</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-600">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Cuci Kering Setrika</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Parfum Standar</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Estimasi 2-3 Hari</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Gratis Jemput (>5kg)</li>
                    </ul>
                    <a href="login.php" class="block w-full py-4 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-center hover:border-blue-500 hover:text-blue-500 transition">Pilih Paket</a>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-2xl border-2 border-blue-500 relative transform md:-translate-y-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs font-bold px-4 py-2 rounded-bl-xl rounded-tr-2xl">POPULER</div>
                    <h3 class="text-lg font-bold text-blue-600 uppercase tracking-widest mb-4">Express Premium</h3>
                    <div class="flex items-baseline mb-6">
                        <span class="text-5xl font-black text-slate-900">Rp12.000</span>
                        <span class="text-slate-500 ml-2">/kg</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-700 font-medium">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> <b>Selesai 6 Jam</b></li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Parfum Grade A (Tahan Lama)</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Packing Plastik & Hanger</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Prioritas Driver</li>
                    </ul>
                    <a href="login.php" class="block w-full py-4 rounded-xl bg-blue-600 text-white font-bold text-center shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Pilih Premium</a>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 hover:-translate-y-2 transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-bold text-slate-500 uppercase tracking-widest mb-4">Satuan / Dry Clean</h3>
                    <div class="flex items-baseline mb-6">
                        <span class="text-2xl font-black text-slate-800">Mulai Rp15rb</span>
                        <span class="text-slate-500 ml-2">/pcs</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-600">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Bed Cover / Selimut</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Jas & Gaun Pesta</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Sepatu & Tas</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500"></i> Treatment Noda Khusus</li>
                    </ul>
                    <a href="https://wa.me/628123456789" class="block w-full py-4 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-center hover:border-blue-500 hover:text-blue-500 transition">Hubungi CS</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-300 py-16 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-2">
                <h2 class="text-3xl font-bold text-white mb-6">Laundry<span class="text-blue-500">Go</span></h2>
                <p class="text-slate-400 leading-relaxed max-w-sm">Platform laundry digital pertama di Indonesia yang mengutamakan kecepatan, kebersihan, dan teknologi. Pakaian bersih, hati senang.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Tautan</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition">Cek Resi</a></li>
                    <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3"><i class="fab fa-whatsapp text-green-500 text-xl"></i> +62 812 3456 7890</li>
                    <li class="flex items-center gap-3"><i class="far fa-envelope text-blue-500 text-xl"></i> hello@laundrygo.id</li>
                    <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-red-500 text-xl"></i> Jakarta Selatan, ID</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-slate-800 pt-8 text-center text-sm">
            <p>&copy; 2025 LaundryGo Indonesia. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Mobile Menu Logic
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>