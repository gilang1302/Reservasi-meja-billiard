@extends('layouts.app')

@section('title', 'Denah Meja Interaktif')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-map-location-dot text-[#6C3BFF]"></i> Pilih Meja Billiard
        </h2>
        <p class="text-sm text-slate-400 mt-1">Gunakan peta interaktif di bawah untuk memilih meja favorit Anda berdasarkan slot waktu.</p>
    </div>

    <!-- Filter Slot Waktu -->
    <div class="p-6 rounded-3xl glass-panel border border-slate-800/80">
        <form method="GET" action="{{ route('booking.meja') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tanggal Bermain</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fa-solid fa-calendar-day"></i></span>
                    <input type="date" name="date" value="{{ $date }}" min="{{ now()->format('Y-m-d') }}" required
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Jam Mulai</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fa-regular fa-clock"></i></span>
                    <select name="start_time" required class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                        <option value="">-- Pilih Jam --</option>
                        @for($h=7; $h<=22; $h++)
                            @php 
                                $timeStr = sprintf('%02d:00', $h);
                                $display = sprintf('%02d:00 %s', $h, $h >= 17 ? '(Peak Hour)' : '');
                            @endphp
                            <option value="{{ $timeStr }}" {{ $startTime === $timeStr ? 'selected' : '' }}>{{ $display }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Jam Selesai</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fa-solid fa-clock"></i></span>
                    <select name="end_time" required class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                        <option value="">-- Pilih Jam --</option>
                        @for($h=8; $h<=23; $h++)
                            @php $timeStr = sprintf('%02d:00', $h); @endphp
                            <option value="{{ $timeStr }}" {{ $endTime === $timeStr ? 'selected' : '' }}>{{ $timeStr }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-600 hover:to-[#6C3BFF] text-white text-sm font-bold shadow-lg shadow-purple-900/40 transition-all duration-300">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari Ketersediaan Meja
                </button>
            </div>
        </form>
    </div>

    <!-- Status Legend -->
    <div class="flex flex-wrap gap-6 items-center bg-slate-950/40 p-4 rounded-2xl border border-slate-850">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Legend Status:</span>
        <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500/20 border border-emerald-500/50 glow-green"></span>
            <span class="text-xs font-bold text-emerald-400">Tersedia (Available)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-full bg-red-500/20 border border-red-500/50 glow-red"></span>
            <span class="text-xs font-bold text-red-400">Dipesan / Dipakai (Booked)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-full bg-slate-700/40 border border-slate-500/50"></span>
            <span class="text-xs font-bold text-slate-400">Perbaikan (Maintenance)</span>
        </div>
        @if($isPeakHour)
            <div class="ml-auto px-3 py-1 rounded bg-orange-500/10 border border-orange-500/20 text-[#FF6B35] text-[11px] font-extrabold uppercase tracking-wide flex items-center gap-1.5 animate-pulse">
                <i class="fa-solid fa-fire text-xs"></i> Tarif Peak Hour (+50%) Berlaku
            </div>
        @endif
    </div>

    <!-- Layout Hall Plan & Detail Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Interactive Hall Map (2/3 width on desktop) -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 border border-slate-800/80">
            <div class="mb-4 flex justify-between items-center bg-slate-950/30 p-3 rounded-xl border border-slate-900">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-300">Peta Tata Letak Hall Billiard</h3>
                <span class="text-xs text-slate-500 font-medium">Klik meja untuk melihat detail</span>
            </div>

            <!-- Hall Grid Area (4 Columns Grid Layout matches coordinate positions) -->
            <div class="grid grid-cols-4 gap-6 bg-slate-950/80 p-8 rounded-2xl border border-slate-900 relative">
                <!-- Bar / Counter simulated area at the top -->
                <div class="col-span-4 bg-slate-900 border border-slate-800 text-center py-2.5 rounded-xl text-xs font-extrabold text-slate-400 tracking-wider uppercase mb-4 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-mug-hot text-[#FF6B35]"></i> Area Bar & Kasir CueMaster
                </div>

                @foreach($tables->sortBy('position_y')->sortBy('position_x') as $table)
                    @php
                        $isBooked = in_array($table->id, $bookedTableIds) || $table->status === 'Occupied' || $table->status === 'Booked';
                        $isMaintenance = $table->status === 'Maintenance';
                        
                        $statusClass = 'border-emerald-500/40 bg-emerald-950/15 hover:bg-emerald-950/30 text-emerald-300 glow-green';
                        if ($isBooked) {
                            $statusClass = 'border-red-500/40 bg-red-950/15 hover:bg-red-950/20 text-red-400 cursor-not-allowed';
                        } elseif ($isMaintenance) {
                            $statusClass = 'border-slate-700/60 bg-slate-900/40 text-slate-500 cursor-not-allowed';
                        }
                    @endphp
                    
                    <button type="button" 
                            id="table-btn-{{ $table->id }}"
                            class="p-5 rounded-2xl border flex flex-col items-center justify-center gap-2.5 transition-all duration-300 transform hover:scale-[1.03] group {{ $statusClass }}"
                            {{ $isBooked || $isMaintenance ? 'disabled' : '' }}
                            onclick="selectTable('{{ json_encode($table) }}', {{ $isBooked ? 'true' : 'false' }}, {{ $isMaintenance ? 'true' : 'false' }})">
                        
                        <div class="relative">
                            <!-- Pool Table Icon -->
                            <i class="fa-solid fa-rectangle-ad text-3xl opacity-80 group-hover:opacity-100 transition-opacity"></i>
                            <!-- Table pocket/dots representation -->
                            <span class="absolute -top-1 -left-1 w-1.5 h-1.5 bg-black rounded-full"></span>
                            <span class="absolute -top-1 -right-1 w-1.5 h-1.5 bg-black rounded-full"></span>
                            <span class="absolute -bottom-1 -left-1 w-1.5 h-1.5 bg-black rounded-full"></span>
                            <span class="absolute -bottom-1 -right-1 w-1.5 h-1.5 bg-black rounded-full"></span>
                        </div>
                        
                        <div class="text-center">
                            <span class="block text-sm font-extrabold">Meja {{ $table->table_number }}</span>
                            <span class="block text-[10px] font-medium tracking-wide uppercase opacity-75">{{ $table->table_type }}</span>
                        </div>

                        <!-- Lamp Indicator (if lamp is on) -->
                        <div class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full {{ $table->lamp_status === 'on' ? 'bg-yellow-400 glow-orange' : 'bg-slate-600' }}"></span>
                            <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400">Lampu</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Details Card (1/3 width on desktop) -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80 flex flex-col justify-between">
            <div class="space-y-6">
                <h3 class="text-lg font-bold border-b border-slate-800 pb-3">Informasi Seleksi</h3>
                
                <div id="selection-placeholder" class="text-center py-12 text-slate-500 space-y-3">
                    <i class="fa-solid fa-circle-info text-4xl block text-slate-600"></i>
                    <p class="text-sm">Silakan cari ketersediaan slot waktu terlebih dahulu, lalu pilih meja yang tersedia pada denah.</p>
                </div>

                <div id="selection-details" class="space-y-5 hidden">
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 flex items-center justify-between">
                        <div>
                            <span id="detail-type" class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-purple-900/50 text-purple-300 border border-purple-800/30">VIP</span>
                            <h4 id="detail-name" class="text-lg font-bold mt-1 text-slate-100">Meja #9</h4>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Harga Off-Peak</span>
                            <span id="detail-price" class="text-sm font-extrabold text-[#FF6B35]">Rp 50.000/jam</span>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div class="flex justify-between items-center text-slate-400">
                            <span>Kapasitas</span>
                            <span class="font-bold text-slate-200">2-4 Pemain</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-400">
                            <span>Harga Peak Hour (17:00-23:00)</span>
                            <span id="detail-peak-price" class="font-bold text-slate-200">Rp 75.000/jam</span>
                        </div>
                        <div class="flex flex-col gap-1 text-slate-400">
                            <span>Deskripsi Fasilitas</span>
                            <p id="detail-desc" class="text-slate-300 font-medium italic mt-0.5">Sofa eksklusif, AC pribadi, room service.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking form (proceed button) -->
            <div class="pt-6 border-t border-slate-850 mt-6">
                @if($startTime && $endTime)
                    <form method="GET" action="{{ route('booking.create', 'placeholder') }}" id="booking-confirm-form" class="hidden">
                        <input type="hidden" name="table_id" id="form-table-id">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="start_time" value="{{ $startTime }}">
                        <input type="hidden" name="end_time" value="{{ $endTime }}">

                        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-green-600 hover:to-emerald-600 text-white text-sm font-bold shadow-lg shadow-emerald-950/40 flex items-center justify-center gap-2 transition-all duration-300">
                            Lanjutkan Reservasi <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                    <button id="booking-disabled-btn" disabled class="w-full py-3 rounded-xl bg-slate-800 text-slate-500 text-sm font-bold border border-slate-700/60 cursor-not-allowed flex items-center justify-center gap-2">
                        Pilih Meja Dahulu
                    </button>
                @else
                    <div class="p-3 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-xs text-center font-medium">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Anda harus menentukan slot waktu di form filter sebelum melanjutkan reservasi.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    let activeSelection = null;

    function selectTable(tableJson, isBooked, isMaintenance) {
        if (isBooked || isMaintenance) return;
        
        const table = typeof tableJson === 'string' ? JSON.parse(tableJson) : tableJson;

        // Reset previous buttons highlight
        if (activeSelection) {
            const prevBtn = document.getElementById(`table-btn-${activeSelection.id}`);
            if (prevBtn) {
                prevBtn.classList.remove('ring-2', 'ring-[#6C3BFF]', 'scale-[1.03]');
            }
        }

        // Highlight selected button
        const btn = document.getElementById(`table-btn-${table.id}`);
        if (btn) {
            btn.classList.add('ring-2', 'ring-[#6C3BFF]', 'scale-[1.03]');
        }

        activeSelection = table;

        // Show details in cards
        document.getElementById('selection-placeholder').classList.add('hidden');
        document.getElementById('selection-details').classList.remove('hidden');

        document.getElementById('detail-name').innerText = `Meja #${table.table_number}`;
        document.getElementById('detail-type').innerText = table.table_type;
        document.getElementById('detail-price').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(table.price_per_hour)}/jam`;
        document.getElementById('detail-peak-price').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(table.price_peak_per_hour)}/jam`;
        document.getElementById('detail-desc').innerText = table.description || 'Tidak ada deskripsi.';

        // Enable Form Submit
        const confirmForm = document.getElementById('booking-confirm-form');
        if (confirmForm) {
            confirmForm.classList.remove('hidden');
            
            // Update form input
            document.getElementById('form-table-id').value = table.id;
            
            // Update action path with table_id parameter
            let action = confirmForm.action;
            confirmForm.action = action.substring(0, action.lastIndexOf('/')) + '/' + table.id;

            // Hide disabled button
            document.getElementById('booking-disabled-btn').classList.add('hidden');
        }
    }
</script>
@endsection
