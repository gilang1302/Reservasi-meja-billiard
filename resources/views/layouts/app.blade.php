<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CueMaster Reserve') - CueMaster Reserve</title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts & CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080810] text-slate-100 min-h-screen flex font-sans antialiased overflow-x-hidden">

    <!-- Sidebar Wrapper -->
    <div x-data="{ sidebarOpen: false }" class="relative flex w-full min-h-screen">
        
        <!-- Sidebar Backdrop for Mobile -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 bg-[#0c0c1e]/90 backdrop-blur-md border-r border-slate-800/80 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:h-auto">
            
            <!-- Logo Section -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-slate-800/80">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#6C3BFF] to-[#FF6B35] flex items-center justify-center shadow-lg shadow-purple-900/40">
                        <i class="fa-solid fa-circle-nodes text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">CueMaster</span>
                        <span class="block text-xs font-semibold tracking-wider uppercase text-[#FF6B35]">Reserve</span>
                    </div>
                </a>
                <!-- Close btn for mobile -->
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Role Badge -->
            <div class="px-6 py-4 border-b border-slate-800/50 bg-slate-900/30">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-slate-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border border-[#080810]"></span>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-slate-200 truncate max-w-[150px]">{{ auth()->user()->name }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-purple-900/60 text-purple-300 border border-purple-800/50">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <p class="px-3 text-[10px] font-bold tracking-wider uppercase text-slate-500 mb-2">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="{{ route('leaderboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('leaderboard') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                    <i class="fa-solid fa-trophy w-5 text-center"></i>
                    <span class="text-sm font-medium">Leaderboard</span>
                </a>

                <!-- ROLE: PELANGGAN -->
                @if(auth()->user()->isPelanggan())
                    <p class="px-3 pt-4 text-[10px] font-bold tracking-wider uppercase text-slate-500 mb-2">Reservasi Meja</p>
                    
                    <a href="{{ route('booking.meja') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('booking.meja') || request()->routeIs('booking.create') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-map-location-dot w-5 text-center"></i>
                        <span class="text-sm font-medium">Denah Meja (Booking)</span>
                    </a>

                    <a href="{{ route('booking.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('booking.history') || request()->routeIs('booking.show') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                        <span class="text-sm font-medium">Riwayat Bermain</span>
                    </a>
                @endif

                <!-- ROLE: KASIR -->
                @if(auth()->user()->isKasir())
                    <p class="px-3 pt-4 text-[10px] font-bold tracking-wider uppercase text-slate-500 mb-2">Operator Panel</p>

                    <a href="{{ route('kasir.tables') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.tables') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-toggle-on w-5 text-center"></i>
                        <span class="text-sm font-medium">Kontrol Meja & Lampu</span>
                    </a>

                    <a href="{{ route('kasir.bookings') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.bookings') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-receipt w-5 text-center"></i>
                        <span class="text-sm font-medium">Reservasi Masuk</span>
                    </a>

                    <a href="{{ route('kasir.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.transactions') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-credit-card w-5 text-center"></i>
                        <span class="text-sm font-medium">Transaksi Pembayaran</span>
                    </a>

                    <a href="{{ route('kasir.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.orders') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-burger w-5 text-center"></i>
                        <span class="text-sm font-medium">Pesanan Kantin F&B</span>
                    </a>
                @endif

                <!-- ROLE: OWNER -->
                @if(auth()->user()->isOwner())
                    <p class="px-3 pt-4 text-[10px] font-bold tracking-wider uppercase text-slate-500 mb-2">Manajemen Bisnis</p>

                    <a href="{{ route('owner.reports') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.reports') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center"></i>
                        <span class="text-sm font-medium">Laporan Keuangan</span>
                    </a>

                    <a href="{{ route('owner.tables.heatmap') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.tables.heatmap') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-fire w-5 text-center"></i>
                        <span class="text-sm font-medium">Heatmap Meja</span>
                    </a>

                    <a href="{{ route('owner.inventory.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.inventory.*') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="text-sm font-medium">Inventaris Alat</span>
                    </a>

                    <a href="{{ route('owner.tables.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.tables.*') && !request()->routeIs('owner.tables.heatmap') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-square-poll-horizontal w-5 text-center"></i>
                        <span class="text-sm font-medium">Pengaturan Meja</span>
                    </a>

                    <a href="{{ route('owner.feedback.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.feedback.index') ? 'bg-[#6C3BFF] text-white shadow-lg shadow-purple-950/50' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }}">
                        <i class="fa-solid fa-star-half-stroke w-5 text-center"></i>
                        <span class="text-sm font-medium">Feedback Pelanggan</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom Actions -->
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-950/20 hover:text-red-300 transition-all duration-200">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        <span class="text-sm font-medium">Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- Navbar Header -->
            <header class="flex items-center justify-between h-20 px-6 lg:px-8 border-b border-slate-800/80 bg-[#0c0c1e]/40 backdrop-blur-md sticky top-0 z-30">
                <!-- Hamburger and Page Title -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="text-slate-300 hover:text-white lg:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    @isset($header)
                        {{ $header }}
                    @else
                        <h1 class="text-lg font-semibold text-slate-100">CueMaster Reserve</h1>
                    @endisset
                </div>

                <!-- Notifications & Dropdowns -->
                <div class="flex items-center gap-4">
                    
                    <!-- Notifications Dropdown (Simulasi / DB) -->
                    @php
                        $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->latest()->take(5)->get();
                        $unreadCount = auth()->user()->unread_notifications_count;
                    @endphp
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative w-10 h-10 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700/60 flex items-center justify-center transition-colors">
                            <i class="fa-regular fa-bell text-slate-300 text-lg"></i>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-[#FF6B35] border-2 border-[#080810] text-[10px] font-bold text-white flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                        
                        <!-- Notification Panel -->
                        <div x-show="open" 
                             @click.outside="open = false" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-80 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl z-50 overflow-hidden" 
                             style="display: none;">
                            
                            <div class="px-4 py-3 border-b border-slate-800 flex items-center justify-between bg-slate-950/40">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="text-[11px] text-[#6C3BFF] hover:underline font-semibold">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-850">
                                @forelse($unreadNotifications as $notif)
                                    <div class="p-4 hover:bg-slate-800/40 transition-colors flex flex-col gap-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-slate-200">{{ $notif->title }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $notif->sent_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-slate-400 line-clamp-2">{{ $notif->message }}</p>
                                        <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="mt-1">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-[#FF6B35] hover:underline font-semibold">
                                                <i class="fa-solid fa-check mr-1"></i>Tandai dibaca
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-slate-500 text-xs">
                                        <i class="fa-regular fa-bell-slash text-2xl mb-2 block"></i>
                                        Tidak ada notifikasi baru
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('notifications.index') }}" class="block text-center py-2.5 text-xs text-slate-400 hover:text-white bg-slate-950/60 border-t border-slate-800 font-medium">
                                Lihat semua notifikasi
                            </a>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 hover:opacity-90 transition-opacity focus:outline-none">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700/60 flex items-center justify-center overflow-hidden">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <span class="font-bold text-slate-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                @endif
                            </div>
                        </button>

                        <div x-show="open" 
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl z-50 overflow-hidden" 
                             style="display: none;">
                            
                            <div class="p-4 border-b border-slate-800 bg-slate-950/40">
                                <span class="block text-xs text-slate-400">Masuk sebagai</span>
                                <span class="block text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name }}</span>
                            </div>

                            <div class="py-1.5">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                                    <i class="fa-solid fa-user text-slate-400"></i> Edit Profil
                                </a>
                                @if(auth()->user()->isPelanggan())
                                    <div class="px-4 py-1.5 my-1 bg-slate-950/30 border-y border-slate-800/40">
                                        <span class="block text-[9px] uppercase tracking-wider font-bold text-slate-500">Tier Member</span>
                                        <span class="text-xs font-bold text-yellow-400"><i class="fa-solid fa-gem mr-1"></i>{{ auth()->user()->member_level }}</span>
                                        <span class="block text-[9px] text-slate-400">{{ auth()->user()->member_poin }} Poin</span>
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-slate-800 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-xs text-red-400 hover:bg-red-950/20 transition-colors">
                                        <i class="fa-solid fa-right-from-bracket text-red-400"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Body -->
            <main class="flex-1 p-6 lg:p-8 max-w-7xl w-full mx-auto">
                
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-start gap-3 shadow-lg shadow-green-950/10">
                        <i class="fa-solid fa-circle-check text-lg mt-0.5"></i>
                        <div>
                            <span class="font-bold text-sm">Berhasil!</span>
                            <p class="text-xs text-green-300 mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-start gap-3 shadow-lg shadow-red-950/10">
                        <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
                        <div>
                            <span class="font-bold text-sm">Gagal!</span>
                            <p class="text-xs text-red-300 mt-0.5">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 flex items-start gap-3 shadow-lg shadow-yellow-950/10">
                        <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i>
                        <div>
                            <span class="font-bold text-sm">Peringatan!</span>
                            <p class="text-xs text-yellow-300 mt-0.5">{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="py-6 border-t border-slate-800/80 bg-[#0c0c1e]/20 text-center text-slate-500 text-xs">
                &copy; {{ date('Y') }} CueMaster Reserve. All rights reserved.
            </footer>
        </div>
    </div>
</body>
</html>
