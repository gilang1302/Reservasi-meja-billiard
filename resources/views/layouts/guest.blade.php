<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Welcome') - CueMaster Reserve</title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts & CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080810] text-slate-100 min-h-screen flex flex-col items-center justify-center font-sans antialiased p-4 relative overflow-hidden">
    
    <!-- Background glow elements -->
    <div class="absolute w-[500px] h-[500px] rounded-full bg-purple-900/10 blur-[120px] -top-60 -left-60 pointer-events-none"></div>
    <div class="absolute w-[500px] h-[500px] rounded-full bg-orange-950/10 blur-[120px] -bottom-60 -right-60 pointer-events-none"></div>

    <div class="w-full sm:max-w-md flex flex-col gap-8 relative z-10">
        <!-- Logo Section -->
        <div class="text-center">
            <a href="/" class="inline-flex flex-col items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#6C3BFF] to-[#FF6B35] flex items-center justify-center shadow-xl shadow-purple-950/40">
                    <i class="fa-solid fa-circle-nodes text-white text-2xl"></i>
                </div>
                <div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">CueMaster</span>
                    <span class="block text-xs font-semibold tracking-widest uppercase text-[#FF6B35]">Reserve</span>
                </div>
            </a>
        </div>

        <!-- Glassmorphism Auth Card -->
        <div class="glass-panel rounded-3xl p-8 shadow-2xl shadow-purple-950/20 border border-slate-800/80">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
