<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CueMaster Reserve - Reservasi Meja Billiard Modern</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080810] text-slate-100 font-sans antialiased overflow-x-hidden">
    
    <!-- Background Glow Elements -->
    <div class="absolute w-[600px] h-[600px] rounded-full bg-purple-900/10 blur-[130px] -top-60 -left-60 pointer-events-none"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full bg-orange-950/10 blur-[130px] top-1/2 right-0 pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="w-full h-20 flex items-center justify-between px-6 lg:px-16 border-b border-slate-900/80 bg-[#080810]/80 backdrop-blur-md fixed top-0 left-0 z-50">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#6C3BFF] to-[#FF6B35] flex items-center justify-center shadow-lg shadow-purple-900/40">
                <i class="fa-solid fa-circle-nodes text-white text-lg"></i>
            </div>
            <div>
                <span class="text-lg font-bold bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">CueMaster</span>
                <span class="block text-xs font-semibold tracking-wider uppercase text-[#FF6B35]">Reserve</span>
            </div>
        </a>

        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-650 hover:to-[#6C3BFF] text-white text-xs font-bold shadow-lg shadow-purple-900/35 transition-all duration-300">
                        Dashboard Operator / Pelanggan
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-300 hover:text-white transition-colors">
                        Masuk / Log In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700/60 transition-colors">
                            Daftar Member
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center pt-24 px-6 text-center relative overflow-hidden">
        <div class="max-w-4xl space-y-8 relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-500/10 border border-purple-500/20 text-[#6C3BFF] text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-star text-[10px]"></i> RESERVASI MEJA BILLIARD MODERN & REAL-TIME
            </div>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight leading-tight">
                Rasakan Sensasi Bermain Terbaik di <br>
                <span class="bg-gradient-to-r from-[#6C3BFF] via-indigo-400 to-[#FF6B35] bg-clip-text text-transparent">CueMaster Hall</span>
            </h1>

            <p class="text-sm md:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Pesan meja billiard favorit Anda secara digital. Lihat ketersediaan real-time, pilih stik premium, pesan makanan langsung dari meja, dan pantau status lampu meja terintegrasi otomatis.
            </p>

            <div class="flex flex-wrap justify-center gap-4 pt-4">
                <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-650 hover:to-[#6C3BFF] text-white font-bold shadow-xl shadow-purple-900/40 flex items-center gap-2.5 transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fa-regular fa-calendar-check text-lg"></i> Mulai Reservasi Sekarang
                </a>
                <a href="#harga" class="px-8 py-4 rounded-xl bg-slate-900/60 hover:bg-slate-800 text-slate-300 font-bold border border-slate-800/80 transition-colors">
                    Lihat Daftar Tarif
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 px-6 lg:px-16 border-t border-slate-900 bg-slate-950/20 relative" id="fitur">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-[#FF6B35]">Kelebihan Platform Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold">Fitur-Fitur Unggulan</h2>
                <div class="w-16 h-1 bg-[#6C3BFF] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-6 rounded-3xl glass-card space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-[#6C3BFF] text-xl">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Denah Meja Interaktif</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Pilih meja favorit Anda secara visual langsung dari peta hall kami. Saring meja berdasarkan tipe Standard, VIP, atau Tournament.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-3xl glass-card space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-[#FF6B35] text-xl">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Kontrol Lampu Otomatis</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Lampu meja billiard terhubung langsung dengan sistem reservasi. Lampu akan menyala otomatis saat sesi dimulai, dan mati saat durasi sewa habis.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-3xl glass-card space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl">
                        <i class="fa-solid fa-burger"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Pemesanan F&B Kantin</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Pesan makanan berat, cemilan, dan minuman dingin langsung dari browser Anda saat bermain. Pesanan akan diantarkan langsung oleh kasir ke meja Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-24 px-6 lg:px-16 border-t border-slate-900" id="harga">
        <div class="max-w-5xl mx-auto space-y-16">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-[#FF6B35]">Daftar Tarif Sewa</span>
                <h2 class="text-3xl md:text-4xl font-bold">Skema Tarif Transparan</h2>
                <p class="text-xs text-slate-400">Harga otomatis berubah saat jam ramai (Peak Hour: 17:00 - 23:00) menggunakan Strategy Pattern.</p>
                <div class="w-16 h-1 bg-[#6C3BFF] mx-auto rounded-full"></div>
            </div>

            <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-slate-500 border-b border-slate-800/80">
                                <th class="pb-4 font-bold uppercase tracking-wider">Kategori Meja</th>
                                <th class="pb-4 font-bold uppercase tracking-wider">Fasilitas Utama</th>
                                <th class="pb-4 font-bold uppercase tracking-wider">Off-Peak (07:00 - 17:00)</th>
                                <th class="pb-4 font-bold uppercase tracking-wider">Peak Hour (17:00 - 23:00)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            <tr>
                                <td class="py-5">
                                    <span class="block font-bold text-slate-200">Standard Table</span>
                                    <span class="text-[10px] text-slate-500 font-semibold uppercase">Meja 1 - 8</span>
                                </td>
                                <td class="py-5 text-slate-400 text-xs">AC Room, Sofa penonton, standard cue set.</td>
                                <td class="py-5 font-bold text-slate-300">Rp 30.000 / jam</td>
                                <td class="py-5 font-bold text-orange-400">Rp 45.000 / jam</td>
                            </tr>
                            <tr>
                                <td class="py-5">
                                    <span class="block font-bold text-yellow-400">VIP Private Room</span>
                                    <span class="text-[10px] text-slate-500 font-semibold uppercase">Meja V1 - V2</span>
                                </td>
                                <td class="py-5 text-slate-400 text-xs">Sofa eksklusif, AC pribadi, Smart TV & Sound System, Room service.</td>
                                <td class="py-5 font-bold text-slate-300">Rp 50.000 / jam</td>
                                <td class="py-5 font-bold text-orange-400">Rp 75.000 / jam</td>
                            </tr>
                            <tr>
                                <td class="py-5">
                                    <span class="block font-bold text-indigo-400">Tournament Table</span>
                                    <span class="text-[10px] text-slate-500 font-semibold uppercase">Meja T1 - T2</span>
                                </td>
                                <td class="py-5 text-slate-400 text-xs">Kain meja Andy Pro / Wiraka L1, Bola turnamen Aramith Duramith.</td>
                                <td class="py-5 font-bold text-slate-300">Rp 60.000 / jam</td>
                                <td class="py-5 font-bold text-orange-400">Rp 90.000 / jam</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer / Authors -->
    <footer class="py-12 border-t border-slate-900/80 bg-slate-950/60 text-center text-slate-500 text-xs space-y-4">
        <div class="max-w-md mx-auto space-y-2">
            <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">Disusun Oleh Kelompok PDPL:</span>
            <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-[11px] text-slate-400 font-semibold max-w-xs mx-auto text-left">
                <span>2272024 Gilang Ardiwilaga</span>
                <span>2372006 Stepanus Sugianto</span>
                <span>2372056 Maverick Rafael T.</span>
                <span>2372059 Syahrial Achmad</span>
                <span>2272040 Daud Panjaitan</span>
            </div>
        </div>
        <p class="pt-4 border-t border-slate-900/50 text-[10px] max-w-xs mx-auto">
            Universitas Kristen Maranatha &bull; Bandung &bull; 2026
        </p>
    </footer>

</body>
</html>
