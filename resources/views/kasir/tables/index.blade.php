@extends('layouts.app')

@section('title', 'Kontrol Meja & Lampu')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-toggle-on text-[#6C3BFF]"></i> Panel Kontrol Meja & Lampu
        </h2>
        <p class="text-sm text-slate-400 mt-1">Gunakan panel ini untuk monitoring real-time, mengontrol lampu otomatis meja, dan mengubah status operasional meja billiard.</p>
    </div>

    <!-- Active Tables Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tables as $table)
            @php
                $isActive = $table->status === 'Occupied';
                $isBooked = $table->status === 'Booked';
                $isMaint = $table->status === 'Maintenance';
                
                $colorClass = 'border-slate-800 bg-slate-900/30';
                if($isActive) {
                    $colorClass = 'border-red-500/20 bg-red-950/5 glow-red';
                } elseif($isBooked) {
                    $colorClass = 'border-yellow-500/20 bg-yellow-950/5';
                } elseif($isMaint) {
                    $colorClass = 'border-slate-900 bg-slate-950/40 text-slate-500';
                }
            @endphp
            
            <div class="p-6 rounded-3xl border flex flex-col justify-between gap-4 {{ $colorClass }} transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-extrabold text-slate-200">Meja {{ $table->table_number }}</span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-slate-800 text-slate-400 border border-slate-700/40">{{ $table->table_type }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 font-mono">ID: {{ $table->id }}</p>
                    </div>

                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                        {{ $table->status === 'Available' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                        {{ $table->status === 'Occupied' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                        {{ $table->status === 'Booked' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : '' }}
                        {{ $table->status === 'Maintenance' ? 'bg-slate-800 text-slate-500 border border-slate-750' : '' }}">
                        {{ $table->status }}
                    </span>
                </div>

                <!-- Session & Player Info -->
                <div class="p-4 rounded-2xl bg-slate-950/40 border border-slate-905 text-xs min-h-[70px] flex flex-col justify-center">
                    @if($isActive && $table->activeBooking)
                        <div class="space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pemain:</span>
                                <span class="font-bold text-slate-300">{{ $table->activeBooking->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Waktu Sesi:</span>
                                <span class="font-bold text-slate-300">{{ $table->activeBooking->start_time->format('H:i') }} - {{ $table->activeBooking->end_time->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Sisa Waktu:</span>
                                <span class="font-extrabold text-orange-400 animate-pulse">{{ $table->activeBooking->remaining_minutes }} Menit</span>
                            </div>
                        </div>
                    @elseif($isBooked && $table->activeBooking)
                        <div class="space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Dipesan Oleh:</span>
                                <span class="font-bold text-slate-300">{{ $table->activeBooking->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Jadwal Main:</span>
                                <span class="font-bold text-yellow-400">{{ $table->activeBooking->start_time->format('H:i') }} - {{ $table->activeBooking->end_time->format('H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-slate-600 italic">Tidak ada sesi aktif</div>
                    @endif
                </div>

                <!-- Lamp Control Switch (AJAX) -->
                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-950/70 border border-slate-900">
                    <span class="text-xs font-bold text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-lg" id="lamp-icon-{{ $table->id }}" style="color: {{ $table->lamp_status === 'on' ? '#fbbf24' : '#64748b' }}"></i>
                        Status Lampu: <span id="lamp-text-{{ $table->id }}" class="font-extrabold uppercase text-[10px]" style="color: {{ $table->lamp_status === 'on' ? '#fbbf24' : '#64748b' }}">{{ $table->lamp_status }}</span>
                    </span>
                    
                    <button type="button" 
                            onclick="ajaxToggleLamp('{{ $table->id }}')"
                            id="lamp-toggle-{{ $table->id }}"
                            class="w-10 h-5.5 rounded-full p-0.5 transition-colors relative flex items-center focus:outline-none {{ $table->lamp_status === 'on' ? 'bg-yellow-500' : 'bg-slate-800 border border-slate-700' }}">
                        <span id="lamp-knob-{{ $table->id }}" class="w-4.5 h-4.5 rounded-full bg-white shadow-sm transform transition-transform {{ $table->lamp_status === 'on' ? 'translate-x-4.5' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                <!-- Administrative Overrides -->
                <div class="flex gap-2">
                    <!-- Complete play or activate booking -->
                    @if($isActive)
                        <form method="POST" action="{{ route('kasir.tables.update-status', $table->id) }}/../activate-session" class="flex-1">
                            @csrf
                            <!-- Wait, TableController completeBooking method is mapped to updateStatus with Available. Let's submit to updateStatus with Available -->
                            <input type="hidden" name="status" value="Available">
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-750 text-slate-200 text-xs font-bold border border-slate-700/60 transition-colors">
                                Selesaikan Sesi
                            </button>
                        </form>
                    @elseif($isBooked)
                        <form method="POST" action="{{ route('kasir.tables.activate-session', $table->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow transition-colors">
                                Mulai Sesi Main
                            </button>
                        </form>
                    @else
                        <!-- Change status dropdown -->
                        <form method="POST" action="{{ route('kasir.tables.update-status', $table->id) }}" class="flex-1 flex gap-2">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-slate-300 text-xs focus:ring-0">
                                <option value="" selected>Ubah Status...</option>
                                <option value="Available" {{ $table->status === 'Available' ? 'disabled' : '' }}>Available</option>
                                <option value="Occupied" {{ $table->status === 'Occupied' ? 'disabled' : '' }}>Occupied (Walk-in)</option>
                                <option value="Maintenance" {{ $table->status === 'Maintenance' ? 'disabled' : '' }}>Maintenance</option>
                            </select>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function ajaxToggleLamp(tableId) {
        fetch(`/kasir/tables/${tableId}/toggle-lamp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Update lamp indicator text
                const text = document.getElementById(`lamp-text-${tableId}`);
                const icon = document.getElementById(`lamp-icon-${tableId}`);
                const toggle = document.getElementById(`lamp-toggle-${tableId}`);
                const knob = document.getElementById(`lamp-knob-${tableId}`);

                text.innerText = data.lamp_status;
                if(data.lamp_status === 'on') {
                    text.style.color = '#fbbf24';
                    icon.style.color = '#fbbf24';
                    toggle.classList.remove('bg-slate-800', 'border', 'border-slate-700');
                    toggle.classList.add('bg-yellow-500');
                    knob.classList.add('translate-x-4.5');
                } else {
                    text.style.color = '#64748b';
                    icon.style.color = '#64748b';
                    toggle.classList.remove('bg-yellow-500');
                    toggle.classList.add('bg-slate-800', 'border', 'border-slate-700');
                    knob.classList.remove('translate-x-4.5');
                }

                // Show alert toast/notification
                showToast(data.message);
            }
        })
        .catch(err => console.error(err));
    }

    function showToast(message) {
        // Simple Toast builder
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 p-4 rounded-xl bg-slate-900 border border-slate-850 text-slate-200 text-xs font-bold shadow-2xl flex items-center gap-2 z-50 transition-all duration-300 transform translate-y-10 opacity-0';
        toast.innerHTML = `<i class="fa-solid fa-circle-info text-[#6C3BFF] text-sm"></i> <span>${message}</span>`;
        
        document.body.appendChild(toast);
        
        // Anim in
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 100);
        
        // Anim out & remove
        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection
