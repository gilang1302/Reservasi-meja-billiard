@extends('layouts.app')

@section('title', 'Heatmap Penggunaan Meja')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <a href="{{ route('dashboard') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-fire text-orange-500"></i> Heatmap Penggunaan Meja Billiard
        </h2>
        <p class="text-sm text-slate-400 mt-1">Representasi visual tingkat intensitas sewa meja. Meja dengan warna merah pekat paling sering disewa pelanggan.</p>
    </div>

    <!-- Heatmap Grid Hall Map -->
    <div class="glass-panel rounded-3xl p-8 border border-slate-800/80">
        <div class="mb-6 flex items-center justify-between bg-slate-950/40 p-4 rounded-xl border border-slate-850">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Gradien Intensitas Penggunaan:</span>
            <div class="flex items-center gap-3 text-xs">
                <span class="text-slate-500">Dingin (Larang Disewa)</span>
                <div class="w-32 h-3.5 bg-gradient-to-r from-blue-900 via-purple-800 to-red-650 rounded-full border border-slate-800"></div>
                <span class="text-slate-300 font-bold">Panas (Sering Disewa)</span>
            </div>
        </div>

        <!-- CSS Coordinates Layout Grid (4 columns) -->
        <div class="grid grid-cols-4 gap-6 bg-slate-950/90 p-8 rounded-2xl border border-slate-900">
            <!-- Simulated counter area -->
            <div class="col-span-4 bg-slate-900/50 border border-slate-850 text-center py-2 rounded-xl text-[10px] font-extrabold tracking-wider uppercase text-slate-500">
                Area Bar & Kasir Utama
            </div>

            @foreach($tables as $table)
                @php
                    $usage = $table->usage_count;
                    $ratio = $maxUsage > 0 ? $usage / $maxUsage : 0;
                    
                    // Interpolate colors from cool blue to hot red based on ratio
                    // 0% usage: HSL 240 (blue)
                    // 100% usage: HSL 0 (red)
                    $hue = 240 - ($ratio * 240);
                    $bgStyle = "background-color: hsla({$hue}, 80%, 40%, 0.15); border-color: hsla({$hue}, 80%, 50%, 0.4); color: hsla({$hue}, 80%, 80%, 1);";
                    $glowStyle = "box-shadow: 0 0 15px hsla({$hue}, 80%, 50%, 0.25);";
                @endphp
                
                <div class="p-6 rounded-2xl border flex flex-col items-center justify-center gap-3" 
                     style="{{ $bgStyle }} {{ $glowStyle }}">
                    
                    <i class="fa-solid fa-rectangle-ad text-3xl"></i>
                    
                    <div class="text-center">
                        <span class="block text-sm font-extrabold">Meja {{ $table->table_number }}</span>
                        <span class="block text-[10px] uppercase font-bold tracking-wide opacity-80">{{ $table->table_type }}</span>
                    </div>

                    <div class="px-2.5 py-1 rounded bg-black/40 border border-white/5 text-[10px] font-extrabold">
                        {{ $usage }} Sesi Sewa
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
