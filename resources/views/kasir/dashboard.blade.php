@extends('layouts.app')

@section('title', 'Operator Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-desktop text-[#6C3BFF]"></i> Panel Operator Kasir
            </h2>
            <p class="text-sm text-slate-400 mt-1">Kelola lampu meja secara otomatis, verifikasi pembayaran digital, dan monitoring aktivitas sewa.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('kasir.tables') }}" class="px-4 py-2.5 rounded-xl bg-[#6C3BFF] hover:bg-indigo-650 text-white text-xs font-bold shadow-lg shadow-purple-900/40 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-toggle-on"></i> Kontrol Meja
            </a>
            <a href="{{ route('kasir.orders') }}" class="px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold shadow-lg shadow-orange-950/40 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-burger"></i> Pesanan Kantin ({{ $pendingOrders->count() }})
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Meja Aktif (Occupied)</span>
                <span class="block text-2xl font-extrabold text-purple-400 mt-1">{{ $tables->where('status', 'Occupied')->count() }} Meja</span>
            </div>
            <i class="fa-solid fa-play text-2xl text-purple-950"></i>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Verifikasi Transaksi</span>
                <span class="block text-2xl font-extrabold text-yellow-500 mt-1">{{ $pendingTransactions->count() }} Menunggu</span>
            </div>
            <i class="fa-solid fa-wallet text-2xl text-yellow-950"></i>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Order F&B Kantin</span>
                <span class="block text-2xl font-extrabold text-orange-500 mt-1">{{ $pendingOrders->count() }} Pending</span>
            </div>
            <i class="fa-solid fa-burger text-2xl text-orange-950"></i>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total Meja Ready</span>
                <span class="block text-2xl font-extrabold text-emerald-400 mt-1">{{ $tables->where('status', 'Available')->count() }} Meja</span>
            </div>
            <i class="fa-solid fa-circle-check text-2xl text-emerald-950"></i>
        </div>
    </div>

    <!-- Active Tables Grid & Control Panel -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <h3 class="text-md font-bold mb-6 border-b border-slate-805 pb-3 uppercase tracking-wider text-slate-400">Monitoring Meja Billiard</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($tables as $table)
                @php
                    $isActive = $table->status === 'Occupied';
                    $isBooked = $table->status === 'Booked';
                    $isMaint = $table->status === 'Maintenance';
                    
                    $colorClass = 'border-emerald-500/30 bg-emerald-950/5 text-emerald-400';
                    if($isActive) {
                        $colorClass = 'border-red-500/30 bg-red-950/10 text-red-400 glow-red';
                    } elseif($isBooked) {
                        $colorClass = 'border-yellow-500/30 bg-yellow-950/5 text-yellow-450';
                    } elseif($isMaint) {
                        $colorClass = 'border-slate-800 bg-slate-900/20 text-slate-500';
                    }
                @endphp
                
                <div class="p-4 rounded-2xl border flex flex-col justify-between items-center text-center gap-3 relative {{ $colorClass }}">
                    <!-- Table Number Badge -->
                    <span class="absolute top-2 left-2 text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-slate-950/80 text-slate-300">#{{ $table->table_number }}</span>
                    
                    <!-- status marker -->
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full {{ $isActive ? 'bg-red-500 glow-red' : ($isBooked ? 'bg-yellow-400 glow-orange' : ($isMaint ? 'bg-slate-650' : 'bg-green-500 glow-green')) }}"></span>
                    
                    <div class="pt-3">
                        <i class="fa-solid fa-rectangle-ad text-3xl"></i>
                        <span class="block text-xs font-extrabold tracking-wider uppercase mt-1.5">{{ $table->table_type }}</span>
                    </div>

                    <!-- Lamp Toggle Switch -->
                    <div class="w-full pt-2 border-t border-slate-850 flex items-center justify-between gap-2">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-500 flex items-center gap-1">
                            <i class="fa-solid fa-lightbulb" style="color: {{ $table->lamp_status === 'on' ? '#fbbf24' : '#64748b' }}"></i> Lampu
                        </span>
                        
                        <form method="POST" action="{{ route('kasir.tables.toggle-lamp', $table->id) }}">
                            @csrf
                            <button type="submit" class="w-10 h-5.5 rounded-full p-0.5 transition-colors relative flex items-center focus:outline-none {{ $table->lamp_status === 'on' ? 'bg-yellow-500' : 'bg-slate-800 border border-slate-700' }}">
                                <span class="w-4.5 h-4.5 rounded-full bg-white shadow-sm transform transition-transform {{ $table->lamp_status === 'on' ? 'translate-x-4.5' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </div>

                    <!-- Action buttons based on status -->
                    <div class="w-full mt-1.5">
                        @if($isActive)
                            <!-- Occupied: show remaining time -->
                            @php
                                $actBk = $table->activeBooking;
                            @endphp
                            @if($actBk)
                                <div class="text-[10px] font-bold text-slate-300 mt-1">
                                    Sisa: <span class="text-orange-400">{{ $actBk->remaining_minutes }} m</span>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('kasir.tables.update-status', $table->id) }}" class="mt-2">
                                @csrf
                                <input type="hidden" name="status" value="Available">
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-200 text-[10px] font-bold transition-colors">
                                    Sudah Selesai
                                </button>
                            </form>
                        @elseif($isBooked)
                            <!-- Booked: activate play session -->
                            <form method="POST" action="{{ route('kasir.tables.activate-session', $table->id) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-[10px] font-bold shadow shadow-yellow-950/20 transition-colors">
                                    Mulai Sesi Main
                                </button>
                            </form>
                        @elseif($table->status === 'Available')
                            <!-- Available: maintenance option -->
                            <form method="POST" action="{{ route('kasir.tables.update-status', $table->id) }}" class="mt-2">
                                @csrf
                                <input type="hidden" name="status" value="Maintenance">
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800/80 text-slate-400 text-[10px] font-medium border border-slate-800/80 transition-colors">
                                    Maintenance
                                </button>
                            </form>
                        @else
                            <!-- Maintenance: release option -->
                            <form method="POST" action="{{ route('kasir.tables.update-status', $table->id) }}" class="mt-2">
                                @csrf
                                <input type="hidden" name="status" value="Available">
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold transition-colors">
                                    Release Meja
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Lists Panel: Verifications & Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Pending Transactions verifications -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
            <div class="flex items-center justify-between border-b border-slate-805 pb-3 mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Verifikasi Bukti Pembayaran</h3>
                <a href="{{ route('kasir.transactions') }}" class="text-xs text-[#6C3BFF] hover:underline font-semibold">Lihat Semua</a>
            </div>

            <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                @forelse($pendingTransactions as $tx)
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-850 flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-200">#{{ $tx->booking_id }}</span>
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase bg-blue-900/50 text-blue-300">{{ $tx->payment_method }}</span>
                            </div>
                            <span class="block text-[10px] text-slate-500">Oleh: {{ $tx->booking->user->name }}</span>
                            <span class="block text-xs font-extrabold text-[#FF6B35]">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($tx->payment_proof)
                                <a href="{{ asset('storage/' . $tx->payment_proof) }}" target="_blank" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs" title="Lihat Bukti">
                                    <i class="fa-solid fa-image"></i>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('kasir.transactions.verify', $tx->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="Success">
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold transition-colors">
                                    Terima
                                </button>
                            </form>
                            <form method="POST" action="{{ route('kasir.transactions.verify', $tx->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="Failed">
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-650 hover:bg-red-600 text-white text-[10px] font-bold transition-colors">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500 text-xs">
                        <i class="fa-solid fa-clipboard-check text-3xl mb-2 block"></i>
                        Tidak ada verifikasi pembayaran baru
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending F&B Orders from playing clients -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
            <div class="flex items-center justify-between border-b border-slate-805 pb-3 mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Order F&B Baru</h3>
                <a href="{{ route('kasir.orders') }}" class="text-xs text-[#6C3BFF] hover:underline font-semibold">Kelola Pesanan</a>
            </div>

            <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                @forelse($pendingOrders as $order)
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-850 flex flex-col gap-2">
                        <div class="flex items-center justify-between text-xs border-b border-slate-850 pb-2">
                            <div>
                                <span class="font-bold text-slate-200">Meja #{{ $order->booking->table->table_number }}</span>
                                <span class="text-[10px] text-slate-500 block">Pesanan: #{{ $order->id }}</span>
                            </div>
                            <form method="POST" action="{{ route('kasir.orders.update-status', $order->id) }}">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-[10px] font-bold text-orange-400 focus:ring-0">
                                    <option value="Pending" selected>Pending</option>
                                    <option value="Processing">Proses Dapur</option>
                                    <option value="Ready">Siap Antar</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Batal</option>
                                </select>
                            </form>
                        </div>
                        <ul class="space-y-1 pl-4 list-disc text-xs text-slate-400">
                            @foreach($order->details as $det)
                                <li>{{ $det->item_name }} ({{ $det->quantity }}x)</li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500 text-xs">
                        <i class="fa-solid fa-mug-hot text-3xl mb-2 block"></i>
                        Tidak ada pesanan F&B pending
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
