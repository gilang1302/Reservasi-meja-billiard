@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header & Quick Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 rounded-3xl bg-gradient-to-r from-purple-950/40 to-slate-900/60 border border-slate-800/80">
        <div>
            <h2 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-slate-400 mt-1">Selamat datang kembali di CueMaster Reserve. Siap untuk bermain hari ini?</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('booking.meja') }}" class="px-5 py-3 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-600 hover:to-[#6C3BFF] text-white text-sm font-bold shadow-lg shadow-purple-900/40 flex items-center gap-2 transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-play"></i> Pesan Meja Sekarang
            </a>
            <a href="{{ route('leaderboard') }}" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold border border-slate-700/60 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-trophy text-yellow-400"></i> Klasemen
            </a>
        </div>
    </div>

    <!-- Active Booking Session (If Any) -->
    @if($activeBooking)
        <div class="p-6 rounded-3xl border border-purple-500/30 bg-purple-950/15 glow-purple relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-40 h-40 bg-purple-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold uppercase bg-purple-500/20 text-purple-300 border border-purple-500/30">Sesi Aktif</span>
                        <span class="text-sm text-slate-400 font-medium">Meja #{{ $activeBooking->table->table_number }} ({{ $activeBooking->table->table_type }})</span>
                    </div>
                    <h3 class="text-xl font-bold">Sedang Bermain</h3>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-300">
                        <div><i class="fa-regular fa-clock mr-2 text-purple-400"></i>{{ $activeBooking->start_time->format('H:i') }} - {{ $activeBooking->end_time->format('H:i') }}</div>
                        <div x-data="{ 
                            remaining: {{ $activeBooking->remaining_minutes }}, 
                            init() {
                                setInterval(() => {
                                    if(this.remaining > 0) this.remaining--;
                                }, 60000);
                            }
                        }">
                            <i class="fa-solid fa-hourglass-half mr-2 text-orange-400 animate-pulse"></i>
                            Sisa Waktu: <span class="font-extrabold text-orange-400" x-text="remaining + ' menit'"></span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('order.menu', $activeBooking->id) }}" class="px-4 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold shadow-lg shadow-orange-950/40 flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-burger"></i> Pesan Makanan/Minuman
                    </a>
                    
                    @if($activeBooking->canExtend())
                        <form method="POST" action="{{ route('booking.extend', $activeBooking->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-lg bg-[#6C3BFF] hover:bg-indigo-600 text-white text-xs font-bold shadow-lg shadow-purple-950/40 flex items-center gap-2 transition-colors">
                                <i class="fa-solid fa-clock"></i> Perpanjang 1 Jam
                            </button>
                        </form>
                    @else
                        <button disabled class="px-4 py-2.5 rounded-lg bg-slate-800 text-slate-500 text-xs font-bold border border-slate-700/50 cursor-not-allowed flex items-center gap-2" title="Meja sudah di-booking pengguna lain pada slot berikutnya">
                            <i class="fa-solid fa-ban"></i> Perpanjang Waktu
                        </button>
                    @endif

                    <a href="{{ route('booking.show', $activeBooking->id) }}" class="px-4 py-2.5 rounded-lg bg-slate-850 hover:bg-slate-800 text-slate-300 text-xs font-bold border border-slate-700/60 flex items-center gap-2 transition-colors">
                        Lihat Rincian <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile & Loyalty Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Membership Card -->
        <div class="p-6 rounded-3xl glass-panel relative overflow-hidden flex flex-col justify-between h-48 group">
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-gradient-to-br from-yellow-500/20 to-orange-500/5 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Level Anggota</span>
                    <h3 class="text-2xl font-extrabold mt-1 text-yellow-400 flex items-center gap-2">
                        <i class="fa-solid fa-gem"></i> {{ auth()->user()->member_level }}
                    </h3>
                </div>
                <span class="text-2xl text-slate-600/80 font-bold tracking-widest">CM</span>
            </div>
            
            <div class="space-y-1">
                <div class="flex justify-between text-xs font-bold text-slate-400">
                    <span>Loyalty Benefit</span>
                    <span>{{ auth()->user()->member_discount * 100 }}% OFF</span>
                </div>
                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full" 
                         style="width: {{ auth()->user()->member_level === 'Platinum' ? '100' : (auth()->user()->member_level === 'Gold' ? '70' : '30') }}%"></div>
                </div>
            </div>
        </div>

        <!-- Loyalty Points Card -->
        <div class="p-6 rounded-3xl glass-panel relative overflow-hidden flex flex-col justify-between h-48 group">
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-gradient-to-br from-blue-500/20 to-purple-500/5 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Poin Loyalitas</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-extrabold text-blue-400">{{ auth()->user()->member_poin ?? 0 }}</span>
                    <span class="text-xs text-slate-500 font-bold">PTS</span>
                </div>
            </div>
            <p class="text-xs text-slate-400">Dapatkan 10 poin setiap bermain selama 1 jam. Tukarkan dengan voucher diskon sewa meja!</p>
        </div>

        <!-- Gameplay Analytics Card -->
        <div class="p-6 rounded-3xl glass-panel relative overflow-hidden flex flex-col justify-between h-48 group">
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-gradient-to-br from-emerald-500/20 to-teal-500/5 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Bermain & Pengeluaran</span>
                <div class="flex justify-between items-baseline mt-2">
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-extrabold text-emerald-400">{{ auth()->user()->total_hours_played ?? 0 }}</span>
                        <span class="text-xs text-slate-500 font-bold">Jam</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-400 block">Total Pengeluaran</span>
                        <span class="text-sm font-extrabold text-emerald-400">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="text-xs text-slate-400 flex justify-between items-center bg-slate-900/40 p-2.5 rounded-xl border border-slate-800/60">
                <span>Peringkat Klasemen</span>
                <span class="font-extrabold text-yellow-400"><i class="fa-solid fa-medal mr-1"></i>#{{ $myRank }} dari {{ \App\Models\User::where('role', 'pelanggan')->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Bookings History -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold">Aktivitas Reservasi Terakhir</h3>
            <a href="{{ route('booking.history') }}" class="text-xs font-semibold text-[#6C3BFF] hover:underline">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold">ID Booking</th>
                        <th class="pb-3 font-semibold">Meja</th>
                        <th class="pb-3 font-semibold">Waktu Bermain</th>
                        <th class="pb-3 font-semibold">Total Biaya</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 font-bold text-slate-300">#{{ $booking->id }}</td>
                            <td class="py-4">
                                <span class="block font-semibold">Meja {{ $booking->table->table_number }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->table->table_type }}</span>
                            </td>
                            <td class="py-4">
                                <span class="block text-slate-300">{{ $booking->start_time->format('d M Y') }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }} ({{ $booking->duration_hours }} Jam)</span>
                            </td>
                            <td class="py-4 font-semibold text-slate-300">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="py-4">
                                @if($booking->status === 'Pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-950 text-yellow-400 border border-yellow-800/30">Menunggu Pembayaran</span>
                                @elseif($booking->status === 'Confirmed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-950 text-blue-400 border border-blue-800/30">Telah Dikonfirmasi</span>
                                @elseif($booking->status === 'Active')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-950 text-purple-400 border border-purple-800/30">Sedang Bermain</span>
                                @elseif($booking->status === 'Completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-950 text-green-400 border border-green-800/30">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-950 text-red-400 border border-red-800/30">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('booking.show', $booking->id) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors" title="Lihat Rincian">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    
                                    @if($booking->status === 'Pending')
                                        <a href="{{ route('transaction.payment', $booking->id) }}" class="px-2.5 py-1 rounded bg-[#FF6B35] hover:bg-orange-500 text-white text-xs font-bold transition-colors">
                                            Bayar
                                        </a>
                                    @endif

                                    @if($booking->status === 'Completed' && !$booking->hasFeedback())
                                        <a href="{{ route('feedback.create', $booking->id) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-[#6C3BFF] text-[#6C3BFF] hover:text-white border border-[#6C3BFF]/30 text-xs font-bold transition-colors">
                                            Beri Rating
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                <i class="fa-regular fa-calendar-times text-3xl mb-2 block"></i>
                                Belum ada aktivitas reservasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
